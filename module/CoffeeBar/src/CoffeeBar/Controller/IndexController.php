<?php

namespace CoffeeBar\Controller ;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $cache = $this->serviceLocator->get('TabCache');
        return array('result' => $cache) ;
    }
    
    public function flushAction()
    {
        $cache = $this->serviceLocator->get('Cache\Persistence') ;
        $cache->flush() ;
        return $this->redirect()->toRoute('home') ;
    }
}