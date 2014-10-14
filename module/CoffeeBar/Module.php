<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CoffeeBar;

use CoffeeBar\Command\OpenTab;
use CoffeeBar\Command\PlaceOrder;
use CoffeeBar\Entity\TabAggregate;
use CoffeeBar\Form\MenuSelect;
use CoffeeBar\Form\WaiterSelect;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\Mvc\MvcEvent ;

class Module implements FormElementProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $sm = $event->getApplication()->getServiceManager() ;
        $em = $sm->get('TabEventManager');
        $em->attachAggregate($sm->get('TabAggregate')) ;
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getFormElementConfig() {
        return array(
            'factories' => array(
                'WaiterSelect' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator() ;
                    $waiters = $serviceLocator->get('CoffeeBarEntity\Waiters') ;
                    $select = new WaiterSelect($waiters) ;
                    return $select ;
                },
                'MenuSelect' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator() ;
                    $menus = $serviceLocator->get('CoffeeBarEntity\MenuItems') ;
                    $select = new MenuSelect($menus) ;
                    return $select ;
                },
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'CoffeeBarEntity\Waiters' => 'CoffeeBar\Entity\Waiters',
                'CoffeeBarEntity\MenuItems' => 'CoffeeBar\Entity\MenuItems',
                'TabEventManager' => 'CoffeeBar\Event\TabEventManager',
            ),
            'factories' => array(
                'OpenTabForm' => function($sm) {
                    $formManager = $sm->get('FormElementManager') ;
                    $form = $formManager->get('CoffeeBar\Form\OpenTabForm') ;
                    $form->setObject($sm->get('OpenTabCommand')) ;
                    return $form ;
                },
                'OpenTabCommand' => function($sm) {
                    $tab = $sm->get('TabEventManager') ;
                    $openTab = new OpenTab() ;
                    $openTab->setEventManager($tab) ;
                    return $openTab ;
                },
                'PlaceOrderCommand' => function($sm) {
                    $tab = $sm->get('TabEventManager') ;
                    $placeOrder = new PlaceOrder() ;
                    $placeOrder->setEventManager($tab) ;
                    return $placeOrder ;
                },
                'TabAggregate' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $tab = new TabAggregate() ;
                    $tab->setEventManager($events) ;
                    return $tab ;
                },
            ),
        ) ;
    }
}
