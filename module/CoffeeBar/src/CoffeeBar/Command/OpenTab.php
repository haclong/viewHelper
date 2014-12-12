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

// OpenTab déclenche un événement. Il faut donc que l’objet puisse avoir 
// accès à un Event Manager pour y déclencher l’événement
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
    
// propriété liées à l’interface EventManagerAwareInterface
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
    
    // la méthode populate() est obligatoire si on veut utiliser l’hydrator ArraySerializable()
    // Or l’hydrator ArraySerializable est le seul hydrator exposé par Zend Framework qui permet
    // d’hydrater un objet avec une fonction personnalisée
    // Nous avons besoin de la fonction personnalisée pour déclencher l’événement au moment
    // où on hydrate l’objet...
    public function populate($data = array()) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->tableNumber = (isset($data['tableNumber'])) ? $data['tableNumber'] : null;
        $this->waiter = (isset($data['waiter'])) ? $data['waiter'] : null; 
        $this->date = new DateTime() ;
        
        if($this->openTabs->isTableActive($this->tableNumber))
        {
            throw new TabAlreadyOpened('Tab is already opened') ;
        } else {
            // on déclenche l’événement.
            // on passera en paramètre l’objet OpenTab que nous venons de définir
            $this->events->trigger('openTab', '', array('openTab' => $this)) ;
        }
    }

    // la méthode getArrayCopy() est obligatoire pour l’hydrator ArraySerializable()
    public function getArrayCopy() {
        return array(
            'id' => $this->id, 
            'tableNumber' => $this->tableNumber, 
            'waiter' => $this->waiter,
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
        return array('id', 'waiter', 'tableNumber', 'date') ;
    }
}