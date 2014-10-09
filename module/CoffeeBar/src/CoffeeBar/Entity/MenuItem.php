<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

class MenuItem
{
    protected $id ; // int
    protected $description ; // string
    protected $price ; // float
    protected $isDrink ; // bool

    public function __construct($id, $description, $price = 0.00, $isDrink = false)
    {
        $this->setId($id) ;
        $this->setDescription($description) ;
        $this->setPrice($price) ;
        $this->setIsDrink($isDrink) ;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getIsDrink() {
        return $this->isDrink;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setIsDrink($isDrink) {
        $this->isDrink = $isDrink;
    }
}
