<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

use CoffeeBar\Event\TabOpened;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;

class TabAggregate implements ListenerAggregateInterface 
{
    protected $open = false ;
    protected $listeners = array() ;
    protected $events ;
    protected $id ;

    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }
     
    public function getEventManager()
    {
        return $this->events;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('openTab', array($this, 'onOpenTab'));
        $this->listeners[] = $events->attach('tabOpened', array($this, 'onTabOpened'));
        $this->listeners[] = $events->attach('placeOrder', array($this, 'onPlaceOrder')) ;
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    } 
    
    public function setId($id)
    {
        $this->id = $id ;
    }
    public function getId()
    {
        return $this->id ;
    }
    
    public function onOpenTab($events)
    {
        $openTab = $events->getParam(0) ;
        var_dump('je suis ici') ;
        $openedTab = new TabOpened() ;
        $openedTab->setId($openTab->getId()) ;
        $openedTab->setTableNumber($openTab->getTableNumber()) ;
        $openedTab->setWaiter($openTab->getWaiter()) ;
        $this->events->trigger('tabOpened', $this, array('tabOpened' => $openedTab)) ;
var_dump($openedTab) ;
        return $openedTab ;
    }
    
    public function onTabOpened($events)
    {
        $this->open = true ;
    }
    
    public function onPlaceOrder($events)
    {
        
    }

}