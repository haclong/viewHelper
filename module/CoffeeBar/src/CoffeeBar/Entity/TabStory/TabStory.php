<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity\TabStory ;

class TabStory
{
    const CLOSE = false ;
    const OPEN = true ;

    /**
     * Tab Identifiant
     * @var int (Guid)
     */
    protected $id ;
    
    /**
     * Tab ouverte ou pas
     * @var bool
     */
    protected $status ;
    
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
     * liste des plats commandés, non préparés
     * @var OrderedItems
     */
    protected $outstandingFood ;
    
    /**
     * liste des plats préparés, non servis
     * @var OrderedItems
     */
    protected $preparedFood ;
    
    /**
     *
     * @var float ;
     */
    protected $itemsServedValue ;
    
    public function __construct()
    {
        $this->status = self::CLOSE ;
        $this->eventsLoaded = array() ;
        $this->outstandingDrinks = new OrderedItems() ;
        $this->outstandingFood = new OrderedItems() ;
        $this->preparedFood = new OrderedItems() ;
        $this->itemsServedValue = 0 ;
    }

    public function getItemsServedValue() {
        return $this->itemsServedValue;
    }

    public function setItemsServedValue($itemsServedValue) {
        $this->itemsServedValue = $itemsServedValue;
    }
    
    public function addValue($value)
    {
        $this->itemsServedValue += $value ;
        return $this->itemsServedValue ;
    }

    public function getId() {
        return $this->id;
    }

    public function getEventsLoaded() {
        return $this->eventsLoaded ;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function addEvents($event) {
        $this->eventsLoaded[] = $event ;
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
    
    public function addPreparedFood($food)
    {
        foreach($food as $item)
        {
            $this->preparedFood->offsetSet(NULL, $item) ;
        }
    }
    
    public function isTabOpened()
    {
        return $this->status ;
    }
    
    public function openTab()
    {
        $this->status = self::OPEN ;
        return $this ;
    }
    
    public function closeTab()
    {
        $this->status = self::CLOSE ;
        return $this ;
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