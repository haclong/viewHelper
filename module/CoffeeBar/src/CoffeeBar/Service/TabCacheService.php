<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Service ;

class TabCacheService
{
    protected $cache ;
    
    public function getCache() {
        return $this->cache;
    }

    public function setCache($cache) {
        $this->cache = $cache;
    }
    
    public function setOpenTabs($openTabs)
    {
        if($this->cache->hasItem('openTabs'))
        {
            return $this->cache->getItem('openTabs') ;
        } else {
            return $this->cache->setItem('openTabs', $openTabs) ;
        }
    }
    
    public function getOpenTabs()
    {
        try {
            return unserialize($this->cache->getItem('openTabs')) ;
        } catch (MissingKeyException $ex) {
            echo 'openTabs cache key missing' ;
        }
    }
    
    public function setTodoList($todoList)
    {
        if($this->cache->hasItem('todoList'))
        {
            return $this->cache->getItem('todoList') ;
        } else {
            return $this->cache->setItem('todoList', $todoList) ;
        }
    }
    
    public function getTodoList()
    {
        try {
            return unserialize($this->cache->getItem('todoList')) ;
        } catch (MissingKeyException $ex) {
            echo 'todoList cache key missing' ;
        }
    }
}
