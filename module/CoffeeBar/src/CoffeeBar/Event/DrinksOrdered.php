<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Event ;

use CoffeeBar\Entity\TabStory\OrderedItems;
use DateTime;

class DrinksOrdered
{
    protected $id ; // guid
    protected $items ; // CoffeeBar\Entity\TabStory\OrderedItems
    protected $date ; // DateTime
    
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

    public function getDate() {
        return $this->date;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }
}