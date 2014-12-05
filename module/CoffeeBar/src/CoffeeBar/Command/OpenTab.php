<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Command ;

use CoffeeBar\Exception\TabAlreadyOpened;
use DateTime;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class OpenTab implements EventManagerAwareInterface 
{
    /**
     * string $id
     */
    protected $id ;
    
    /**
     * string $tableNumber ;
     */
    protected $tableNumber ;
    
    /**
     * string $waiter
     */
    protected $waiter ;
    
    protected $events ;
    
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

    public function setOpenTabs($openTabs)
    {
        $this->openTabs = $openTabs ;
    }
    
    public function populate($data = array()) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tableNumber = (isset($data['tableNumber'])) ? $data['tableNumber'] : null;
        $this->waiter = (isset($data['waiter'])) ? $data['waiter'] : null; 
        $this->date = new DateTime() ;
        
        if($this->openTabs->isTableActive($this->tableNumber))
        {
            throw new TabAlreadyOpened('Tab is already opened') ;
        } else {
            $this->events->trigger('openTab', '', array('openTab' => $this)) ;
        }
    }

    public function getArrayCopy() {
        return array(
            'id' => $this->id, 
            'tableNumber' => $this->tableNumber, 
            'waiter' => $this->waiter,
            'date' => $this->date,
                ) ;
    }

    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }
     
    public function getEventManager()
    {
        return $this->events;
    }
    
    public function __sleep()
    {
        return array('id', 'waiter', 'tableNumber', 'date') ;
    }
}