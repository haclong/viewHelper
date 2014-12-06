<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Event ;

use DateTime;

class FoodPrepared
{
    /**
     *
     * @var int - table number
     */
    protected $id ;
    
    /**
     *
     * @var array - menu numbers
     */
    protected $food ;
    
    /**
     *
     * @var DateTime
     */
    protected $date ;
    
    public function getId() {
        return $this->id;
    }

    public function getFood() {
        return $this->food;
    }

    public function getDate() {
        return $this->date;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFood($food) {
        $this->food = $food;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }
}