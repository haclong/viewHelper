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
        $this->eventsLoaded[] = $event ;
        $this->eventsCount++ ;
    }
    
    public function addOutstandingDrinks($drinks)
    {
        $this->outstandingDrinks = $drinks ;
    }
    
    public function addOutstandingFood($food)
    {
        $this->outstandingFood = $food ;
    }
    
    public function isEventLoaded($eventName)
    {
        if(in_array($eventName, $this->eventsLoaded))
        {
            return TRUE ;
        } else {
            return FALSE ;
        }
    }
}