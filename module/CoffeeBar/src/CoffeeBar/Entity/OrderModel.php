<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

use CoffeeBar\Entity\OrderItems;

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

    function setItems(array $items) {
        $this->items = new OrderItems($items) ;
    }

    public function populate($data = array()) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->items = (isset($data['items'])) ? new OrderItems($data['items']) : null;
    }
    
    public function getArrayCopy() {
        return array(
            'id' => $this->id, 
            'items' => $this->items->getArrayCopy(), 
                ) ;
    }
}