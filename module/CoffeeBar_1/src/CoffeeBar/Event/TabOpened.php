<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Event ;

use DateTime;

class TabOpened
{
    /**
     * string $id
     */
    protected $id ;
    
    /**
     * string $tableNumber
     */
    protected $tableNumber ;
    
    /**
     * string $waiter
     */
    protected $waiter ;
    
    /**
     *
     * @var DateTime
     */
    protected $date ;

    public function getId() {
        return $this->id;
    }

    public function getTableNumber() {
        return $this->tableNumber;
    }

    public function getWaiter() {
        return $this->waiter;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTableNumber($tableNumber) {
        $this->tableNumber = $tableNumber;
    }

    public function setWaiter($waiter) {
        $this->waiter = $waiter;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }
}
