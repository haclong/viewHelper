<?php

namespace CoffeeBar\Controller ;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $cache = $this->serviceLocator->get('Cache\Persistence');
        return array('result' => $cache) ;
    }
}