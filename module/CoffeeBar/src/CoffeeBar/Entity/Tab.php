<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

class Tab
{
    protected $tableNumber;
    protected $waiter;
    protected $itemsToServe;
    protected $itemsInPreparation;
    protected $itemsServed;
    
    public function __construct($tableNumber, $waiter, $itemsToServe, $itemsInPreparation, $itemsServed)
    {
        $this->setTableNumber($tableNumber) ;
        $this->setWaiter($waiter) ;
        $this->setItemsToServe($itemsToServe) ;
        $this->setItemsInPreparation($itemsInPreparation) ;
        $this->setItemsServed($itemsServed) ;
    }
    
    public function getTableNumber() {
        return $this->tableNumber;
    }

    public function getWaiter() {
        return $this->waiter;
    }

    public function getItemsToServe() {
        return $this->itemsToServe;
    }

    public function getItemsInPreparation() {
        return $this->itemsInPreparation;
    }

    public function getItemsServed() {
        return $this->itemsServed;
    }

    public function setTableNumber($tableNumber) {
        $this->tableNumber = $tableNumber;
    }

    public function setWaiter($waiter) {
        $this->waiter = $waiter;
    }

    public function setItemsToServe($itemsToServe) {
        $this->itemsToServe = $itemsToServe;
    }
    
    public function addItemsToServe($itemsToServe) {
        foreach($itemsToServe as $item)
        {
            $this->itemsToServe->offsetSet(NULL, $item) ;
        }
    }
    
    public function addItemToServe(TabItem $itemToServe) {
        $this->itemsToServe->offsetSet(NULL, $itemToServe) ;
    }

    public function setItemsInPreparation($itemsInPreparation) {
        $this->itemsInPreparation = $itemsInPreparation;
    }

    public function setItemsServed($itemsServed) {
        $this->itemsServed = $itemsServed;
    }
}