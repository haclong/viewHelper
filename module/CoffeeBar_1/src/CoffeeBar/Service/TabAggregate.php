<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Service ;

use CoffeeBar\Event\TabClosed;
use CoffeeBar\Exception\MustPayEnough;
use CoffeeBar\Exception\TabAlreadyClosed;
use DateTime;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('closeTab', array($this, 'onCloseTab')) ;
    }
    
    
    
    public function onCloseTab($events)
    {
        $closeTab = $events->getParam('closeTab') ;

        $story = $this->loadStory($closeTab->getId()) ;

        if($story->getItemsServedValue() > $closeTab->getAmountPaid())
        {
            throw new MustPayEnough('Le solde n\'y est pas, compléter l\'addition') ;
        }
        if(!$story->isTabOpened())
        {
            throw new TabAlreadyClosed('La note est fermée') ;
        }

        $tabClosed = new TabClosed() ;
        $tabClosed->setId($closeTab->getId()) ;
        $tabClosed->setAmountPaid($closeTab->getAmountPaid()) ;
        $tabClosed->setOrderValue($story->getItemsServedValue()) ;
        $tabClosed->setTipValue($closeTab->getAmountPaid() - $story->getItemsServedValue()) ;
        $tabClosed->setDate(new DateTime()) ;

        $this->events->trigger('tabClosed', $this, array('tabClosed' => $tabClosed)) ;
    }
    
    public function onTabClosed($events)
    {
        $tabClosed = $events->getParam('tabClosed') ;
        
        $story = $this->loadStory($tabClosed->getId()) ;
        $story->addEvents($tabClosed) ;
        $story->closeTab() ;
        $this->saveStory($tabClosed->getId(), $story) ;
    }
    
}