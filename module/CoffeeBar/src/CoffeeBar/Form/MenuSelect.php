<?php

namespace CoffeeBar\Form ;

use CoffeeBar\Entity\MenuItems;
use Zend\Form\Element\Select;

class MenuSelect extends Select
{
    protected $menus ;
            
    public function __construct(MenuItems $items)
    {
        $this->menus = $items ;
    }

    public function init()
    {
        $this->setValueOptions($this->menus->getSelectValues()) ;
    }
}
