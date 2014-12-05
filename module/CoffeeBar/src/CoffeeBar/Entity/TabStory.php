<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

use ArrayObject;

class TabStory
{
    /**
     * Tab Identifiant
     * @var int (Guid)
     */
    protected $id ;
    
    /**
     * Count of events loaded
     * @var int
     */
    protected $eventsCount ;
    
    /**
     * Array of events
     * @var array
     */
    protected $eventsLoaded ;
    
    /**
     * liste des boissons commandées, non servies
     * @var orderedItems
     */
    protected $outstandingDrinks ;
    
    /**
     * liste des plats commandés, non servis
     * @var OrderedItems
     */
    protected $outstandingFood ;
    
    /**
     *
     * @var type 
     */
    protected $preparedFood ;
    
    public function __construct()
    {
        $this->eventsCount = 0 ;
        $this->eventsLoaded = array() ;
        $this->outstandingDrinks = new OrderedItems() ;
        $this->outstandingFood = new OrderedItems() ;
    }

    public function getId() {
        return $this->id;
    }

    public function getEventsCount() {
        return $this->eventsCount ;
    }

    public function getEventsLoaded() {
        return $this->eventsLoaded ;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function addEvents($event) {
        $this->eventsLoaded[get_class($event)] = $event ;
        $this->eventsCount++ ;
    }
    
    public function getOutstandingDrinks() {
        return $this->outstandingDrinks;
    }

    public function getOutstandingFood() {
        return $this->outstandingFood;
    }

    public function getPreparedFood() {
        return $this->preparedFood;
    }

    public function addOutstandingDrinks($drinks)
    {
        foreach($drinks as $drink)
        {
            $this->outstandingDrinks->offsetSet(NULL, $drink) ;
        }
    }

    public function addOutstandingFood($food)
    {
        foreach($food as $item)
        {
            $this->outstandingFood->offsetSet(NULL, $item) ;
        }
    }
    
    public function isEventLoaded($eventName)
    {
        if(array_key_exists($eventName, $this->eventsLoaded))
        {
            return TRUE ;
        } else {
            return FALSE ;
        }
    }
    
    public function areDrinksOutstanding(array $menuNumbers)
    {
        return $this->areAllInList($menuNumbers, $this->outstandingDrinks) ;
    }
    
    public function isFoodOutstanding(array $menuNumbers)
    {
        return $this->areAllInList($menuNumbers, $this->outstandingFood) ;
    }
    
    public function isFoodPrepared(array $menuNumbers)
    {
        return $this->areAllInList($menuNumbers, $this->preparedFood) ;
    }
    
    protected function areAllInList(array $want, OrderedItems $have)
    {
        $currentHave = $this->getOrderedItemsId($have) ;
        foreach($want as $item)
        {
            if(($key = array_search($item, $currentHave)) !== false) {
                unset($currentHave[$key]);
            } else {
                return false ;
            }
        }
        return true ;
    }

    protected function getOrderedItemsId(OrderedItems $items)
    {
        $array = array() ;
        foreach($items as $item)
        {
            $array[] = $item->getId() ;
        }
        return $array ;
    }
}