<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Service ;

use CoffeeBar\Entity\OpenTabs\TabInvoice;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class OpenTabs implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('drinksServed', array($this, 'onDrinksServed')) ;
        $this->listeners[] = $events->attach('foodServed', array($this, 'onFoodServed')) ;
        $this->listeners[] = $events->attach('tabClosed', array($this, 'onTabClosed')) ;
    }
    
    /**
     * Move the served items from the itemsToServe list to the itemsServed list
     * @param Events $events
     */
    public function onDrinksServed($events)
    {
        $drinksServed = $events->getParam('drinksServed') ;

        $this->loadTodoByTab() ;
        $tab = $this->getTab($drinksServed->getId()) ;
        
        foreach($drinksServed->getDrinks() as $drink)
        {
            $key = $tab->getItemsToServe()->getKeyByMenuNumber($drink) ;
            if($key !== null)
            {
                $value = $tab->getItemsToServe()->offsetGet($key) ;
                $tab->getItemsServed()->addItem($value) ;
                $tab->getItemsToServe()->offsetUnset($key) ;
            }
        }
        $this->todoByTab->offsetSet($drinksServed->getId(), $tab) ;
        $this->saveTodoByTab() ;
    }
    
    /**
     * Move the served items from the itemsToServe list to the itemsServed list
     * @param Events $events
     */
    public function onFoodServed($events)
    {
        $foodServed = $events->getParam('foodServed') ;

        $this->loadTodoByTab() ;
        $tab = $this->getTab($foodServed->getId()) ;
        
        foreach($foodServed->getFood() as $food)
        {
            $key = $tab->getItemsToServe()->getKeyByMenuNumber($food) ;
            if($key !== null)
            {
                $value = $tab->getItemsToServe()->offsetGet($key) ;
                $tab->getItemsServed()->addItem($value) ;
                $tab->getItemsToServe()->offsetUnset($key) ;
            }
        }
        $this->todoByTab->offsetSet($foodServed->getId(), $tab) ;
        $this->saveTodoByTab() ;
    }

    /**
     * Listener to tabClosed event
     * @param Events $events
     */
    public function onTabClosed($events)
    {
        $tabClosed = $events->getParam('tabClosed') ;

        $this->loadTodoByTab() ;
        $this->todoByTab->offsetUnset($tabClosed->getId()) ;
        $this->saveTodoByTab() ;
    }

    /**
     * Retourne la liste des éléments à servir
     * @param string $waiter
     * @return array
     */
    public function todoListForWaiter($waiter)
    {
        $this->loadTodoByTab() ;
        $array = array() ;
        foreach($this->todoByTab->getArrayCopy() as $k => $v)
        {
            if($v->getWaiter() == $waiter && count($v->getItemsToServe()) > 0)
            {
                $array[$v->getTableNumber()] = $v->getItemsToServe() ;
            }
        }
        return $array ;
    }

    public function invoiceForTable($table)
    {
        $this->loadTodoByTab() ;
        foreach($this->todoByTab->getArrayCopy() as $k => $v)
        {
            if($v->getTableNumber() == $table)
            {
                $status = new TabInvoice() ;
                $status->setTabId($k) ;
                $status->setTableNumber($v->getTableNumber()) ;
                $status->setItems($v->getItemsServed()) ;
                $status->setHasUnservedItems(count($v->getItemsToServe()) + count($v->getItemsInPreparation())) ;
                return $status ;
            }
        }
        return NULL ;
    }

    protected function getTab($guid)
    {
        $this->loadTodoByTab() ;
        return $this->todoByTab->offsetGet($guid) ;
    }
}
