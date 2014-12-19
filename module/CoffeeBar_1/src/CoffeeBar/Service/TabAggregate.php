<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Service ;

use CoffeeBar\Command\PlaceOrder;
use CoffeeBar\Event\DrinksOrdered;
use CoffeeBar\Event\DrinksServed;
use CoffeeBar\Event\FoodOrdered;
use CoffeeBar\Event\FoodPrepared;
use CoffeeBar\Event\FoodServed;
use CoffeeBar\Event\TabClosed;
use CoffeeBar\Exception\DrinksNotOutstanding;
use CoffeeBar\Exception\FoodNotOutstanding;
use CoffeeBar\Exception\FoodNotPrepared;
use CoffeeBar\Exception\MustPayEnough;
use CoffeeBar\Exception\TabAlreadyClosed;
use CoffeeBar\Exception\TabAlreadyOpened;
use CoffeeBar\Exception\TabNotOpen;
use DateTime;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('placeOrder', array($this, 'onPlaceOrder')) ;
        $this->listeners[] = $events->attach('drinksOrdered', array($this, 'onDrinksOrdered')) ;
        $this->listeners[] = $events->attach('foodOrdered', array($this, 'onFoodOrdered')) ;
        $this->listeners[] = $events->attach('markDrinksServed', array($this, 'onMarkDrinksServed')) ;
        $this->listeners[] = $events->attach('drinksServed', array($this, 'onDrinksServed')) ;
        $this->listeners[] = $events->attach('markFoodPrepared', array($this, 'onMarkFoodPrepared')) ;
        $this->listeners[] = $events->attach('foodPrepared', array($this, 'onFoodPrepared')) ;
        $this->listeners[] = $events->attach('markFoodServed', array($this, 'onMarkFoodServed')) ;
        $this->listeners[] = $events->attach('foodServed', array($this, 'onFoodServed')) ;
        $this->listeners[] = $events->attach('closeTab', array($this, 'onCloseTab')) ;
    }
    
    public function onPlaceOrder($events)
    {
        $placeOrder = $events->getParam('placeOrder') ;

        $story = $this->loadStory($placeOrder->getId()) ;
 
        if(!$story->isTabOpened())
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
        $story->addEvents($drinksOrdered) ;
        $story->addOutstandingDrinks($drinksOrdered->getItems()) ;
        $this->saveStory($drinksOrdered->getId(), $story) ;
    }
    
    public function onFoodOrdered($events)
    {
        $foodOrdered = $events->getParam('foodOrdered') ;
        
        $story = $this->loadStory($foodOrdered->getId()) ;
        $story->addEvents($foodOrdered) ;
        $story->addOutstandingFood($foodOrdered->getItems()) ;
        $this->saveStory($foodOrdered->getId(), $story) ;
    }
    
    public function onMarkDrinksServed($events)
    {
        $markDrinksServed = $events->getParam('markDrinksServed') ;
        
        $story = $this->loadStory($markDrinksServed->getId()) ;

        if(!$story->areDrinksOutstanding($markDrinksServed->getDrinks()))
        {
            throw new DrinksNotOutstanding('une ou plusieurs boissons ne font pas parties de la commande') ;
        }
        
        $drinksServed = new DrinksServed() ;
        $drinksServed->setId($markDrinksServed->getId()) ;
        $drinksServed->setDrinks($markDrinksServed->getDrinks()) ;
        $drinksServed->setDate(new DateTime()) ;

        $this->events->trigger('drinksServed', $this, array('drinksServed' => $drinksServed)) ;
    }

    public function onDrinksServed($events)
    {
        $drinksServed = $events->getParam('drinksServed') ;
        
        $story = $this->loadStory($drinksServed->getId()) ;
        $story->addEvents($drinksServed) ;
        foreach($drinksServed->getDrinks() as $drink)
        {
            $key = $story->getOutstandingDrinks()->getKeyById($drink) ;
            if($key !== null)
            {
                $price = $story->getOutstandingDrinks()->offsetGet($key)->getPrice() ;
                $story->addValue($price) ;
                $story->getOutstandingDrinks()->offsetUnset($key) ;
            } 
        }
        $this->saveStory($drinksServed->getId(), $story) ;
    }

    public function onMarkFoodPrepared($events)
    {
        $markFoodPrepared = $events->getParam('markFoodPrepared') ;
        
        $story = $this->loadStory($markFoodPrepared->getId()) ;

        if(!$story->isFoodOutstanding($markFoodPrepared->getFood()))
        {
            throw new FoodNotOutstanding('un ou plusieurs plats n\'ont pas été commandés') ;
        }
        
        $foodPrepared = new FoodPrepared() ;
        $foodPrepared->setId($markFoodPrepared->getId()) ;
        $foodPrepared->setFood($markFoodPrepared->getFood()) ;
        $foodPrepared->setDate(new DateTime()) ;

        $this->events->trigger('foodPrepared', $this, array('foodPrepared' => $foodPrepared)) ;
    }

    public function onFoodPrepared($events)
    {
        $foodPrepared = $events->getParam('foodPrepared') ; 
        
        $story = $this->loadStory($foodPrepared->getId()) ;
        $story->addEvents($foodPrepared) ;
        
        foreach($foodPrepared->getFood() as $food)
        {
            $key = $story->getOutstandingFood()->getKeyById($food) ;
            
            if($key !== null)
            {
                $value = $story->getOutstandingFood()->offsetGet($key) ;
                $story->getOutstandingFood()->offsetUnset($key) ;
                $story->getPreparedFood()->offsetSet(NULL, $value) ;
            }
        }
        $this->saveStory($foodPrepared->getId(), $story) ;
    }

    public function onMarkFoodServed($events)
    {
        $markFoodServed = $events->getParam('markFoodServed') ;
        
        $story = $this->loadStory($markFoodServed->getId()) ;

        if(!$story->isFoodPrepared($markFoodServed->getFood()))
        {
            throw new FoodNotPrepared('les plats ne sont pas encore prêts') ;
        }
        
        $foodServed = new FoodServed() ;
        $foodServed->setId($markFoodServed->getId()) ;
        $foodServed->setFood($markFoodServed->getFood()) ;
        $foodServed->setDate(new DateTime()) ;

        $this->events->trigger('foodServed', $this, array('foodServed' => $foodServed)) ;
    }

    public function onFoodServed($events)
    {
        $foodServed = $events->getParam('foodServed') ; 

        $story = $this->loadStory($foodServed->getId()) ;
        $story->addEvents($foodServed) ;
        
        foreach($foodServed->getFood() as $food)
        {
            $key = $story->getPreparedFood()->getKeyById($food) ;
           
            if($key !== null)
            {
                $price = $story->getPreparedFood()->offsetGet($key)->getPrice() ;
                $story->addValue($price) ;
                $story->getPreparedFood()->offsetUnset($key) ;
            }
        }
        $this->saveStory($foodServed->getId(), $story) ;
    }
    
    public function onCloseTab($events)
    {
        $closeTab = $events->getParam('closeTab') ;

        $story = $this->loadStory($closeTab->getId()) ;

        if($story->getItemsServedValue() > $closeTab->getAmountPaid())
        {
            throw new MustPayEnough('Le solde n\'y est pas, compléter l\'addition') ;
        }
        if(!$story->isTabOpened())
        {
            throw new TabAlreadyClosed('La note est fermée') ;
        }

        $tabClosed = new TabClosed() ;
        $tabClosed->setId($closeTab->getId()) ;
        $tabClosed->setAmountPaid($closeTab->getAmountPaid()) ;
        $tabClosed->setOrderValue($story->getItemsServedValue()) ;
        $tabClosed->setTipValue($closeTab->getAmountPaid() - $story->getItemsServedValue()) ;
        $tabClosed->setDate(new DateTime()) ;

        $this->events->trigger('tabClosed', $this, array('tabClosed' => $tabClosed)) ;
    }
    
    public function onTabClosed($events)
    {
        $tabClosed = $events->getParam('tabClosed') ;
        
        $story = $this->loadStory($tabClosed->getId()) ;
        $story->addEvents($tabClosed) ;
        $story->closeTab() ;
        $this->saveStory($tabClosed->getId(), $story) ;
    }
    
    protected function orderDrink(PlaceOrder $order)
    {
        $drinks = $order->getItems()->getDrinkableItems() ;
        if(count($drinks) != 0)
        {
            $orderedDrinks = new DrinksOrdered() ;
            $orderedDrinks->setId($order->getId()) ;
            $orderedDrinks->setItems($drinks) ;
            $orderedDrinks->setDate(new DateTime()) ;
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
            $orderedFoods->setDate(new DateTime()) ;
            $this->events->trigger('foodOrdered', $this, array('foodOrdered' => $orderedFoods)) ;
        }
    }
}