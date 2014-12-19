<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity\ChefTodoList;

/**
 * Description of TodoListItem
 *
 * @author haclong
 */
class TodoListItem {

    /**
     * Numéro du menu
     * @var int ;
     */
    protected $menuNumber ;

    /**
     * Nom du menu
     * @var string
     */
    protected $description ;

    public function __construct($menuNumber, $description)
    {
        $this->setMenuNumber($menuNumber) ;
        $this->setDescription($description) ;
    }

    public function getMenuNumber() {
        return $this->menuNumber;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setMenuNumber($menuNumber) {
        $this->menuNumber = $menuNumber;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}
