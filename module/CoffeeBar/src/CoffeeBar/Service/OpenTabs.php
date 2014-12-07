<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Service ;

use ArrayObject;
use CoffeeBar\Entity\OpenTabs\ItemsArray;
use CoffeeBar\Entity\OpenTabs\Tab;
use CoffeeBar\Entity\OpenTabs\TabItem;
use CoffeeBar\Entity\OpenTabs\TabStatus;
use MissingKeyException ;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class OpenTabs implements ListenerAggregateInterface
{
    protected $todoByTab ;
    protected $cache ;
    protected $listeners ;
    
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('tabOpened', array($this, 'onTabOpened'));
        $this->listeners[] = $events->attach('drinksOrdered', array($this, 'onDrinksOrdered')) ;
        $this->listeners[] = $events->attach('foodOrdered', array($this, 'onFoodOrdered')) ;
        $this->listeners[] = $events->attach('foodPrepared', array($this, 'onFoodPrepared')) ;
        $this->listeners[] = $events->attach('drinksServed', array($this, 'onDrinksServed')) ;
        $this->listeners[] = $events->attach('foodServed', array($this, 'onFoodServed')) ;
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    public function setCache($cache)
    {
        $this->cache = $cache ;
    }
    public function getCache()
    {
        return $this->cache ;
    }
    
    protected function loadTodoByTab()
    {
        try {
            $this->todoByTab = unserialize($this->cache->getItem('openTabs')) ;
        } catch (MissingKeyException $ex) {
            echo $ex->getMessage() . ' - openTabs cache key missing' ;
        }
    }
    protected function saveTodoByTab()
    {
        $this->cache->setItem('openTabs', serialize($this->todoByTab)) ;
    }
    
    protected function setTodoByTab()
    {
        try {
            $this->todoByTab = unserialize($this->cache->getItem('openTabs')) ;
        } catch (MissingKeyException $ex) {
            echo $ex->getMessage() . ' - openTabs cache key missing' ;
        }
    }
    
    /**
     * Listener to tabOpened event
     * @param Events $events
     */
    public function onTabOpened($events)
    {
        $this->loadTodoByTab() ;
        $to = $events->getParam('tabOpened') ;
        
        $tab = new Tab($to->getTableNumber(), $to->getWaiter(), new ItemsArray(), new ItemsArray(), new ItemsArray()) ;
        $this->todoByTab->offsetSet($to->getId(), $tab) ;
        $this->saveTodoByTab() ;
    }
    
    public function onDrinksOrdered($events)
    {
        $drinksOrdered = $events->getParam('drinksOrdered') ;

        $this->loadTodoByTab() ;
        $tab = $this->getTab($drinksOrdered->getId()) ;
        
        foreach($drinksOrdered->getItems() as $drink)
        {
            $item = new TabItem($drink->getId(), $drink->getDescription(), $drink->getPrice()) ;
            $tab->getItemsToServe()->addItem($item) ;
        }
        
        $this->todoByTab->offsetSet($drinksOrdered->getId(), $tab) ;
        $this->saveTodoByTab() ;
    }

    /**
     * Listener add food ordered tab content
     * @param Events $events
     */
    public function onFoodOrdered($events)
    {
        $foodOrdered = $events->getParam('foodOrdered') ;

        $this->loadTodoByTab() ;
        $tab = $this->getTab($foodOrdered->getId()) ;
        
        foreach($foodOrdered->getItems() as $food)
        {
            $item = new TabItem($food->getId(), $food->getDescription(), $food->getPrice()) ;
            $tab->getItemsInPreparation()->addItem($item) ;
        }
        
        $this->todoByTab->offsetSet($foodOrdered->getId(), $tab) ;
        $this->saveTodoByTab() ;
    }
    
    /**
     * Move the prepared items from the itemsInPreparation list to the itemsToServe list
     * @param Events $events
     */
    public function onFoodPrepared($events)
    {
        $foodPrepared = $events->getParam('foodPrepared') ;

        $this->loadTodoByTab() ;
        $tab = $this->getTab($foodPrepared->getId()) ;
        
        foreach($foodPrepared->getFood() as $food)
        {
            $key = $tab->getItemsInPreparation()->getKeyByMenuNumber($food) ;
            if($key !== null)
            {
                $value = $tab->getItemsInPreparation()->offsetGet($key) ;
                $tab->getItemsToServe()->addItem($value) ;
                $tab->getItemsInPreparation()->offsetUnset($key) ;
            }
        }
        $this->todoByTab->offsetSet($foodPrepared->getId(), $tab) ;
        $this->saveTodoByTab() ;
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
     * Retourne la liste des tables servies
     * @return ArrayObject
     */
    public function activeTableNumbers()
    {
        $this->loadTodoByTab() ;
        $array = array() ;
        foreach($this->todoByTab->getArrayCopy() as $k => $v)
        {
            $array[] = $v->getTableNumber() ;
        }
        sort($array) ;
        return new ArrayObject($array) ;
    }
    
    /**
     * Retourne l'id de la table
     * @param int $table - Numéro de la table
     * @return id
     */
    public function tabIdForTable($table)
    {
        $this->loadTodoByTab() ;
        foreach($this->todoByTab->getArrayCopy() as $k => $v)
        {
            if($v->getTableNumber() == $table)
            {
                return $k ;
            }
        }
        return NULL ;
    }

    /**
     * Retourne le statut de la commande
     * @param int $table - Id de la table
     * @return TabStatus
     */
    public function tabForTable($table)
    {
        $this->loadTodoByTab() ;
        foreach($this->todoByTab->getArrayCopy() as $k => $v)
        {
            if($v->getTableNumber() == $table)
            {
                $status = new TabStatus() ;
                $status->setTabId($k) ;
                $status->setTableNumber($v->getTableNumber()) ;
                $status->setItemsToServe($v->getItemsToServe()) ;
                $status->setItemsInPreparation($v->getItemsInPreparation()) ;
                $status->setItemsServed($v->getItemsServed()) ;
                return $status ;
            }
        }
        return NULL ;
    }
    
    /**
     * Retourne un booléen si la table est déjà ouverte ou pas
     * @param int $id - Numéro de la table
     */
    public function isTableActive($id)
    {
        $activeTableNumbers = $this->activeTableNumbers() ;
        if(in_array($id, $activeTableNumbers->getArrayCopy()))
        {
            return TRUE ;
        } else {
            return FALSE ;
        }
    }
    
    /**
     * Retourne la liste des éléments à servir
     * @param string $waiter
     * @return ArrayObject
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

    protected function getTab($guid)
    {
        $this->loadTodoByTab() ;
        $tabs = $this->todoByTab->getArrayCopy() ;
        return $tabs[$guid] ;
    }
}

//    public class OpenTabs : IOpenTabQueries,
//        ISubscribeTo<TabClosed>
//    {
//        public TabInvoice InvoiceForTable(int table)
//        {
//            KeyValuePair<Guid, Tab> tab;
//            lock (todoByTab)
//                tab = todoByTab.First(t => t.Value.TableNumber == table);
//
//            lock (tab.Value)
//                return new TabInvoice
//                {
//                    TabId = tab.Key,
//                    TableNumber = tab.Value.TableNumber,
//                    Items = new List<TabItem>(tab.Value.Served),
//                    Total = tab.Value.Served.Sum(i => i.Price),
//                    HasUnservedItems = tab.Value.InPreparation.Any() || tab.Value.ToServe.Any()
//                };
//        }
//
//        public void Handle(TabClosed e)
//        {
//            lock (todoByTab)
//                todoByTab.Remove(e.Id);
//        }
//}
