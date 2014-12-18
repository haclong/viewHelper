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

class MarkFoodPrepared implements EventManagerAwareInterface 
{
    /**
     *
     * @var int - id unique
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
    
    /**
     *
     * @var Events
     */
    protected $events ;

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

    public function setFood(array $food) {
        $this->food = $food;
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
        return array('id', 'food', 'date') ;
    }
    
    public function markPrepared($id, $menuNumbers)
    {
        $this->setId($id) ;
        $this->setFood($menuNumbers) ;
        $this->setDate(new DateTime()) ;
        $this->events->trigger('markFoodPrepared', '', array('markFoodPrepared' => $this)) ;
    }
}