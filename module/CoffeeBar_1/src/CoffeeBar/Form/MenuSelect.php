<?php

namespace CoffeeBar\Form ;

use CoffeeBar\Entity\MenuItems;
use Zend\Form\Element\Select;

class MenuSelect extends Select
{
    protected $menus ;
    
    // injecter l'objet MenuItems dans le constructeur
    public function __construct(MenuItems $items)
    {
        $this->menus = $items ;
    }

    // assinger le tableau avec les paires id/value
    public function init()
    {
        $this->setValueOptions($this->menus->getSelectValues()) ;
    }
}
