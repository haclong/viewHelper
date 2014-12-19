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

class CloseTab implements EventManagerAwareInterface
{
    protected $id ;
    protected $amountPaid ;
// propriété liées à l’interface EventManagerAwareInterface
    protected $events ;

    /**
     *
     * @var DateTime
     */
    protected $date ;

    function getId() {
        return $this->id;
    }

    function getAmountPaid() {
        return $this->amountPaid;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setAmountPaid($amountPaid) {
        $this->amountPaid = $amountPaid;
    }
    
    public function getDate() {
        return $this->date;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }

    public function populate($data = array()) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->amountPaid = (isset($data['amountPaid'])) ? $data['amountPaid'] : null; 
        $this->date = new DateTime() ;
        
        $this->events->trigger('closeTab', '', array('closeTab' => $this)) ;
    }
    public function getArrayCopy() {
        return array(
            'id' => $this->id, 
            'amountPaid' => $this->amountPaid, 
            'date' => $this->date,
                ) ;
    }

    // méthode définie par l’interface EventManagerAwareInterface
    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }
     
    // méthode définie par l’interface EventManagerAwareInterface
    public function getEventManager()
    {
        return $this->events;
    }
    
    public function __sleep()
    {
        return array('id', 'amountPaid', 'date') ;
    }
}