<?php

namespace CoffeeBar\Controller ;

use CoffeeBar\Entity\MenuItems;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $menuItems = new MenuItems() ;
        return array('result' => $menuItems) ;
    }
}