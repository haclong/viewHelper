<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Event ;

class FoodOrdered
{
    protected $id ; // int
    protected $items ; // OrderedItems
    
    function getId() {
        return $this->id;
    }

    function getItems() {
        return $this->items;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setItems(OrderedItems $items) {
        $this->items = $items;
    }
}