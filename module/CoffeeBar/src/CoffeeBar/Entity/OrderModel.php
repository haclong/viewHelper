<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

class OrderModel
{
    protected $id ; // int
    protected $items ; // OrderItems
    
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

    public function populate($data = array()) {
        isset($data['id']) ? $this->setId($data['id']) : null;
        isset($data['items']) ? $this->setItems($data['items']) : null;
    }
    
    public function getArrayCopy() {
        return array(
            'id' => $this->id, 
            'items' => $this->items, 
                ) ;
    }
}