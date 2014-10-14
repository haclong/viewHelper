<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Command ;

use ArrayObject;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class PlaceOrder implements EventManagerAwareInterface 
{
    protected $id ; // int
    protected $items ; // ArrayObject
    protected $events ;
    
    function getId() {
        return $this->id;
    }

    function getItems() {
        return $this->items ;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setItems(array $items) {
        $this->items = new ArrayObject($items);
    }
    
    public function populate($data = array()) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->items = (isset($data['items'])) ? $data['items'] : null;
        $this->events->trigger('placeOrder', '', array($this)) ;
    }
    
    public function getArrayCopy() {
        return array(
            'id' => $this->id, 
            'items' => $this->items, 
                ) ;
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
}