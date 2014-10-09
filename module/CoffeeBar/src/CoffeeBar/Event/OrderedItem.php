<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Event ;

class OrderedItem
{
    protected $id ; // int
    protected $description ; // string
    protected $price ; // float
    protected $isDrink ; // bool

    function getId() {
        return $this->id;
    }

    function getDescription() {
        return $this->description;
    }

    function getPrice() {
        return $this->price;
    }

    function getIsDrink() {
        return $this->isDrink;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setIsDrink($isDrink) {
        $this->isDrink = $isDrink;
    }
}