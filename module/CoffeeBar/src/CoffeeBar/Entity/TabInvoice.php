<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

class TabInvoice
{
    protected $tabId;
    protected $tableNumber;
    protected $items;
    protected $total;
    protected $hasUnservedItems;

    public function getTabId() {
        return $this->tabId;
    }

    public function getTableNumber() {
        return $this->tableNumber;
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getHasUnservedItems() {
        return $this->hasUnservedItems;
    }

    public function setTabId($tabId) {
        $this->tabId = $tabId;
    }

    public function setTableNumber($tableNumber) {
        $this->tableNumber = $tableNumber;
    }

    public function setItems($items) {
        $this->items = $items;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function setHasUnservedItems($hasUnservedItems) {
        $this->hasUnservedItems = $hasUnservedItems;
    }
}