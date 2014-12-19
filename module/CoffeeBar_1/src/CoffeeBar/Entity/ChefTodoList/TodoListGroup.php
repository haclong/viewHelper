<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity\ChefTodoList;

use ArrayObject;

/**
 * Description of TodoListGroup
 *
 * @author haclong
 */
class TodoListGroup {
    /**
     * 
     * @var int - guid
     */
    protected $tab ;

    /**
     *
     * @var array
     */
    protected $items ;
    
    public function __construct($tab, ArrayObject $items)
    {
        $this->setTab($tab) ;
        $this->setItems($items) ;
    }

    public function getTab() {
        return $this->tab;
    }

    public function getItems() {
        return $this->items;
    }

    public function setTab($tab) {
        $this->tab = $tab;
    }

    public function setItems($items) {
        $this->items = $items;
    }
    
    public function getKeyByMenuNumber($menuNumber)
    {
        foreach($this->getItems() as $key => $value)
        {
            if($value->getMenuNumber() == $menuNumber)
            {
                return $key ;
            }
        }
    }
}