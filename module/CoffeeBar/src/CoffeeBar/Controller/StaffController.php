<?php

namespace CoffeeBar\Controller ;

use Zend\Mvc\Controller\AbstractActionController;

class StaffController extends AbstractActionController
{
    public function toDoAction()
    {
        $waiter = $this->params()->fromRoute('name');
        $openTabs = $this->serviceLocator->get('OpenTabs') ;
        $list = $openTabs->todoListForWaiter($waiter) ;
        return array('result' => $list, 'waiter' => $waiter) ;
    }
    
    public function indexAction()
    {
        $waiters = $this->serviceLocator->get('CoffeeBarEntity\Waiters') ;
        return array('result' => $waiters) ;
    }
}