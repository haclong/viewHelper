<?php

namespace CoffeeBar\Controller ;

use Zend\Mvc\Controller\AbstractActionController;

class StaffController extends AbstractActionController
{
    public function waiterToDoAction()
    {
        $waiter = $this->params()->fromRoute('name');
        return array('result' => $waiter) ;
    }
    
    public function chefToDoAction()
    {
        return array('result' => '') ;
    }
    
    public function waiterListAction()
    {
        $waiters = $this->serviceLocator->get('CoffeeBarEntity\Waiters') ;
        return array('result' => $waiters) ;
    }
}