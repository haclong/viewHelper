<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity\TabStory ;

class TabStory
{
    /**
     * Array of events
     * @var array
     */
    protected $eventsLoaded ;
    
    
    public function __construct()
    {
        $this->eventsLoaded = array() ;
    }

    public function setItemsServedValue($itemsServedValue) {
        $this->itemsServedValue = $itemsServedValue;
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
}