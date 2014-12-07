<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Service;

use ArrayObject;
use CoffeeBar\Entity\ChefTodoList\TodoListGroup;
use CoffeeBar\Entity\ChefTodoList\TodoListItem;
use CoffeeBar\Entity\OpenTabs\TabItem;
use MissingKeyException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Description of ChefTodoList
 *
 * @author haclong
 */
class ChefTodoList implements ListenerAggregateInterface
{
    protected $todoList ;
    protected $cache ;
    protected $listeners ;
    
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('foodOrdered', array($this, 'onFoodOrdered')) ;
        $this->listeners[] = $events->attach('foodPrepared', array($this, 'onFoodPrepared')) ;
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
    
    protected function loadTodoList()
    {
        try {
            $this->todoList = unserialize($this->cache->getItem('todoList')) ;
        } catch (MissingKeyException $ex) {
            echo $ex->getMessage() . ' - todoList cache key missing' ;
        }
    }
    protected function saveTodoList()
    {
        $this->cache->setItem('todoList', serialize($this->todoList)) ;
    }
    
    protected function setTodoList()
    {
        try {
            $this->todoList = unserialize($this->cache->getItem('todoList')) ;
        } catch (MissingKeyException $ex) {
            echo $ex->getMessage() . ' - todoList cache key missing' ;
        }
    }
    
    public function onFoodOrdered($events)
    {
        $foodOrdered = $events->getParam('foodOrdered') ;

        $this->loadTodoList() ;
        
        $items = new ArrayObject() ;
        foreach($foodOrdered->getItems() as $value)
        {
            $item = new TodoListItem($value->getId(), $value->getDescription()) ;
            $items->offsetSet(NULL, $item) ;
        }

        $group = new TodoListGroup($foodOrdered->getId(), $items) ;
        
        $this->todoList->offsetSet(NULL, $group) ;
        $this->saveTodoList() ;
    }
    
    public function onFoodPrepared($events)
    {
        $foodPrepared = $events->getParam('foodPrepared') ;

        $this->loadTodoList() ;

        foreach($this->todoList as $key => $item)
        {
            if($item->getTab() == $foodPrepared->getId())
            {
                $groupKey = $key ;
                $group = $item ;
            }
        }
        
        foreach($foodPrepared->getFood() as $food)
        {
            $key = $group->getKeyByMenuNumber($food) ;
            if($key !== null)
            {
                $group->getItems()->offsetUnset($key) ;
            }
        }
        
        if(count($group->getItems()) == 0)
        {
            $this->todoList->offsetUnset($groupKey) ;
        }
            
        $this->saveTodoList() ;
    }
    
    public function getList()
    {
        $this->loadTodoList() ;
        return $this->todoList ;
    }
}