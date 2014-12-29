<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity\OpenTabs ;

use ArrayObject;

class TabStatus
{
    protected $tabId; // int (guid) - id unique de la note
    protected $tableNumber; // int - numéro de la table
    protected $itemsToServe; // ItemsArray (ArrayObject) - liste des éléments à servir
    protected $itemsInPreparation; // ItemsArray (ArrayObject) - liste des éléments en préparation
    protected $itemsServed; // ItemsArray (ArrayObject) - liste des éléments servis

    public function getTabId() {
        return $this->tabId;
    }

    public function getTableNumber() {
        return $this->tableNumber;
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

    public function setTabId($tabId) {
        $this->tabId = $tabId;
    }

    public function setTableNumber($tableNumber) {
        $this->tableNumber = $tableNumber;
    }

    public function setItemsToServe(ArrayObject $itemsToServe) {
        $this->itemsToServe = $itemsToServe;
    }

    public function setItemsInPreparation(ArrayObject $itemsInPreparation) {
        $this->itemsInPreparation = $itemsInPreparation;
    }

    public function setItemsServed(ArrayObject $itemsServed) {
        $this->itemsServed = $itemsServed;
    }
}