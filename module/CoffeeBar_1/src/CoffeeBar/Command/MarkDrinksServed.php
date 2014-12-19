<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Command ;

use DateTime;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class MarkDrinksServed implements EventManagerAwareInterface 
{
    /**
     *
     * @var int - Guid
     */
    protected $id ; 
    
    /**
     *
     * @var array
     */
    protected $drinks ;
    
    /**
     *
     * @var DateTime
     */
    protected $date ;
    
    /**
     *
     * @var Events
     */
    protected $events ;

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

    public function setDrinks(array $drinks) {
        $this->drinks = $drinks;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
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
        return array('id', 'drinks', 'date') ;
    }
    
    public function markServed($id, $menuNumbers)
    {
        $this->setId($id) ;
        $this->setDrinks($menuNumbers) ;
        $this->setDate(new DateTime()) ;
        $this->events->trigger('markDrinksServed', '', array('markDrinksServed' => $this)) ;
    }
}