<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

use \ArrayObject;
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
        
        $tab = new Tab($to->getTableNumber(), $to->getWaiter(), new ArrayObject(), new ArrayObject(), new ArrayObject()) ;
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
            $tab->addItemToServe($item) ;
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
            $tab->addItemToServe($item) ;
        }
        
        $this->todoByTab->offsetSet($foodOrdered->getId(), $tab) ;
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
     * @return \CoffeeBar\Entity\TabStatus
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
    
    protected function getTab($guid)
    {
        $this->loadTodoByTab() ;
        $tabs = $this->todoByTab->getArrayCopy() ;
        return $tabs[$guid] ;
    }
}

//    public class OpenTabs : IOpenTabQueries,
//        ISubscribeTo<FoodPrepared>,
//        ISubscribeTo<DrinksServed>,
//        ISubscribeTo<FoodServed>,
//        ISubscribeTo<TabClosed>
//    {
//        public Dictionary<int, List<TabItem>> TodoListForWaiter(string waiter)
//        {
//            lock (todoByTab)
//                return (from tab in todoByTab
//                        where tab.Value.Waiter == waiter
//                        select new
//                        {
//                            TableNumber = tab.Value.TableNumber,
//                            ToServe = CopyItems(tab.Value, t => t.ToServe)
//                        })
//                        .Where(t => t.ToServe.Count > 0)
//                        .ToDictionary(k => k.TableNumber, v => v.ToServe);
//        }
//
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
//        public void Handle(FoodOrdered e)
//        {
//            AddItems(e.Id,
//                e.Items.Select(drink => new TabItem
//                {
//                    MenuNumber = drink.MenuNumber,
//                    Description = drink.Description,
//                    Price = drink.Price
//                }),
//                t => t.InPreparation);
//        }
//
//        public void Handle(FoodPrepared e)
//        {
//            MoveItems(e.Id, e.MenuNumbers, t => t.InPreparation, t => t.ToServe);
//        }
//
//        public void Handle(DrinksServed e)
//        {
//            MoveItems(e.Id, e.MenuNumbers, t => t.ToServe, t => t.Served);
//        }
//
//        public void Handle(FoodServed e)
//        {
//            MoveItems(e.Id, e.MenuNumbers, t => t.ToServe, t => t.Served);
//        }
//
//        public void Handle(TabClosed e)
//        {
//            lock (todoByTab)
//                todoByTab.Remove(e.Id);
//        }
//
//        private void AddItems(Guid tabId, IEnumerable<TabItem> newItems, Func<Tab, List<TabItem>> to)
//        {
//            var tab = getTab(tabId);
//            lock (tab)
//                to(tab).AddRange(newItems);
//        }
//
//        private void MoveItems(Guid tabId, List<int> menuNumbers,
//            Func<Tab, List<TabItem>> from, Func<Tab, List<TabItem>> to)
//        {
//            var tab = getTab(tabId);
//            lock (tab)
//            {
//                var fromList = from(tab);
//                var toList = to(tab);
//                foreach (var num in menuNumbers)
//                {
//                    var serveItem = fromList.First(f => f.MenuNumber == num);
//                    fromList.Remove(serveItem);
//                    toList.Add(serveItem);
//                }
//            }
//        }
//    }
//}
