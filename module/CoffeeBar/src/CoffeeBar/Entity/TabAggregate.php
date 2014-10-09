<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

use CoffeeBar\Event\TabOpened;
use Zend\EventManager\EventManager;

class TabAggregate
{
    protected $events ;
    protected $open = false ;
    
    public function events()
    {
        if(!$this->events) {
            $this->events = new EventManager(__CLASS__);
        }
        
        $this->events->attach('openTab', array($this, 'onOpenTab')) ;
        $this->events->attach('tabOpened', array($this, 'onTabOpened')) ;
        return $this->events ;
    }

    public function onOpenTab($events)
    {
        $openTab = $events->getParam(0) ;
        $openedTab = new TabOpened() ;
        $openedTab->setId($openTab->getId()) ;
        $openedTab->setTableNumber($openTab->getTableNumber()) ;
        $openedTab->setWaiter($openTab->getWaiter()) ;
        $this->events->trigger('tabOpened', $this, array('tabOpened' => $openedTab)) ;
//var_dump($openedTab) ;
        return $openedTab ;
    }
    
    public function onTabOpened($event)
    {
        $this->open = true ;
    }
}