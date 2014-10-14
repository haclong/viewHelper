<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

class OrderItem
{
    protected $id ;
    protected $number ;

    public function getId() {
        return $this->id;
    }

    public function getNumber() {
        return $this->number;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNumber($number) {
        $this->number = $number;
    }


}
