<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Command ;

use DateTime;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class PlaceOrder implements EventManagerAwareInterface 
{
    protected $id ; // int
    protected $items ; // CoffeeBar\Entity\TabStory\OrderedItems
    protected $events ;
    protected $date ; // DateTime
    
    function getId() {
        return $this->id;
    }

    function getItems() {
        return $this->items ;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setItems($items) {
        $this->items = $items ;
    }
    
    public function getDate() {
        return $this->date;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }

    public function placeOrder($id, $items)
    {
        $this->setId($id) ;
        $this->setItems($items) ;
        $this->setDate(new DateTime()) ;
        $this->events->trigger('placeOrder', '', array('placeOrder' => $this)) ;
    }

    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }
     
    public function getEventManager()
    {
        return $this->events;
    }
    
    public function __sleep() {
        return array('id', 'date', 'items');
    }
}