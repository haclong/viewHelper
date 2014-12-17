<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Event ;

use DateTime;

class DrinksServed
{
    /**
     *
     * @var int - id unique de la note
     */
    protected $id ;
    
    /**
     *
     * @var array - menu numbers
     */
    protected $drinks ;
    
    /**
     *
     * @var DateTime
     */
    protected $date ;

    public function getId() {
        return $this->id;
    }

    public function getDrinks() {
        return $this->drinks;
    }

    public function getDate() {
        return $this->date;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDrinks($drinks) {
        $this->drinks = $drinks;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }

}
