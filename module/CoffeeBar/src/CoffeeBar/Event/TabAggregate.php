<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Event ;

use CoffeeBar\Command\PlaceOrder;
use CoffeeBar\Entity\TabStory;
use CoffeeBar\Event\TabOpened;
use CoffeeBar\Exception\TabNotOpen;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class TabAggregate implements ListenerAggregateInterface 
{
    protected $listeners = array() ;
    protected $events ;
    protected $id ;
    protected $cache ;
    protected $tabs ;

    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }
     
    public function getEventManager()
    {
        return $this->events;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('openTab', array($this, 'onOpenTab'));
        $this->listeners[] = $events->attach('tabOpened', array($this, 'onTabOpened'));
        $this->listeners[] = $events->attach('placeOrder', array($this, 'onPlaceOrder')) ;
        $this->listeners[] = $events->attach('drinksOrdered', array($this, 'onDrinksOrdered')) ;
        $this->listeners[] = $events->attach('foodOrdered', array($this, 'onFoodOrdered')) ;
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    } 
    
    public function setId($id)
    {
        $this->id = $id ;
    }
    public function getId()
    {
        return $this->id ;
    }
    public function getCache() {
        return $this->cache;
    }

    public function setCache($cache) {
        $this->cache = $cache;
    }
    
    /**
     * Load the tab story by id
     * @param string $id - Tab guid
     */
    public function loadStory($id)
    {
        if($this->cache->hasItem($id))
        {
            return unserialize($this->cache->getItem($id)) ;
        } else {
            $story = new TabStory() ;
            $story->setId($id) ;
            return $story ;
        }
    }
    /**
     * Stockage en cache
     * @param string $id - Tab guid
     * @param string $eventName - Event name
     */
    protected function saveStory($id, $story)
    {
        $this->cache->setItem($id, serialize($story)) ;
    }
    
    public function onOpenTab($events)
    {
        $openTab = $events->getParam('openTab') ;
        
        $story = $this->loadStory($openTab->getId()) ;
        $story->addEvents('openTab') ;
        $this->saveStory($openTab->getId(), $story) ;

        $this->setId($openTab->getId()) ;
        $openedTab = new TabOpened() ;
        $openedTab->setId($openTab->getId()) ;
        $openedTab->setTableNumber($openTab->getTableNumber()) ;
        $openedTab->setWaiter($openTab->getWaiter()) ;

        $this->events->trigger('tabOpened', $this, array('tabOpened' => $openedTab)) ;
    }
    
    public function onTabOpened($events)
    {
        $tabOpened = $events->getParam('tabOpened') ;
        $story = $this->loadStory($tabOpened->getId()) ;
        $story->addEvents('tabOpened') ;
        $this->saveStory($tabOpened->getId(), $story) ;

        $this->open = true ;
    }
    
    public function onPlaceOrder($events)
    {
        $placeOrder = $events->getParam('placeOrder') ;

        $story = $this->loadStory($placeOrder->getId()) ;
        $story->addEvents('placeOrder') ;
        $this->saveStory($placeOrder->getId(), $story) ;
 
        if(!$this->isTabOpened($placeOrder->getId()))
        {
            throw new TabNotOpen('Tab is not open yet') ;
        } else {
            $this->orderDrink($placeOrder) ;
            $this->orderFood($placeOrder) ;
        }
    }
    
    public function onDrinksOrdered($events)
    {
        $drinksOrdered = $events->getParam('drinksOrdered') ;
        
        $story = $this->loadStory($drinksOrdered->getId()) ;
        $story->addEvents('drinksOrdered') ;
        $story->addOutstandingDrinks($drinksOrdered->getItems()) ;
        $this->saveStory($drinksOrdered->getId(), $story) ;
    }
    
    public function onFoodOrdered($events)
    {
        $foodOrdered = $events->getParam('foodOrdered') ;
        
        $story = $this->loadStory($foodOrdered->getId()) ;
        $story->addEvents('foodOrdered') ;
        $story->addOutstandingFood($foodOrdered->getItems()) ;
        $this->saveStory($foodOrdered->getId(), $story) ;
    }
    
    protected function isTabOpened($id)
    {
        $story = $this->loadStory($id) ;
        return $story->isEventLoaded('tabOpened') ;
    }
    
    protected function orderDrink(PlaceOrder $order)
    {
        $drinks = $order->getItems()->getDrinkableItems() ;
        if(count($drinks) != 0)
        {
            $orderedDrinks = new DrinksOrdered() ;
            $orderedDrinks->setId($order->getId()) ;
            $orderedDrinks->setItems($drinks) ;
            $this->events->trigger('drinksOrdered', $this, array('drinksOrdered' => $orderedDrinks)) ;
        }
    }
    
    protected function orderFood(PlaceOrder $order)
    {
        $foods = $order->getItems()->getEatableItems() ;
        if(count($foods) != 0)
        {
            $orderedFoods = new FoodOrdered() ;
            $orderedFoods->setId($order->getId()) ;
            $orderedFoods->setItems($foods) ;
            $this->events->trigger('foodOrdered', $this, array('foodOrdered' => $orderedFoods)) ;
        }
    }
}