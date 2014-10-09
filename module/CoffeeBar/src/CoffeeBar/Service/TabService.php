<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Service ;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class TabService implements EventManagerAwareInterface
{
    protected $eventManager ;
    
    public function setEventManager(EventManagerInterface $eventManager) {
        $this->eventManager = $eventManager ;
    }

    public function getEventManager() {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

}