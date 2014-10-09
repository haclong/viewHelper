<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Factory ;

use CoffeeBar\Controller\TabController;
use Zend\ServiceManager\FactoryInterface ;
use Zend\ServiceManager\ServiceLocatorInterface ;

class TabControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $sm = $serviceLocator->getServiceLocator() ;
        $tab = $sm->get('CoffeeBar\Entity\TabAggregate') ;
        $em = $tab->events() ;
        return new TabController($em) ;
    }
}