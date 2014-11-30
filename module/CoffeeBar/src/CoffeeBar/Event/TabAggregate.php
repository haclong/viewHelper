<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Event ;

use CoffeeBar\Entity\TabStory;
use CoffeeBar\Event\TabOpened;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class TabAggregate implements ListenerAggregateInterface 
{
    protected $open = false ;
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
    public function loadTab($id)
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
    public function saveTab($id, $eventName)
    {
        $story = $this->loadTab($id) ;
        $story->addEvents($eventName) ;
        $this->cache->setItem($id, serialize($story)) ;
    }
    
    public function onOpenTab($events)
    {
        $openTab = $events->getParam(0) ;
        
        $this->saveTab($openTab->getId(), 'onOpenTab') ;

        $this->setId($openTab->getId()) ;

//        var_dump('je suis ici') ;
//        var_dump($events) ;
        $openedTab = new TabOpened() ;
        $openedTab->setId($openTab->getId()) ;
        $openedTab->setTableNumber($openTab->getTableNumber()) ;
        $openedTab->setWaiter($openTab->getWaiter()) ;

        $this->events->trigger('tabOpened', $this, array('tabOpened' => $openedTab)) ;
        var_dump(unserialize($this->cache->getItem($openTab->getId()))) ;
//        var_dump($openedTab) ;
//        return $openedTab ;
    }
    
    public function onTabOpened($events)
    {
        $this->open = true ;
    }
    
    public function onPlaceOrder($events)
    {
        
    }

}