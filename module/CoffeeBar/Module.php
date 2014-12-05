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
use CoffeeBar\Command\MarkDrinksServed;
use CoffeeBar\Command\MarkFoodServed;
use CoffeeBar\Entity\OpenTabs;
use CoffeeBar\Entity\TodoByTab;
use CoffeeBar\Event\TabAggregate;
use CoffeeBar\Form\MenuSelect;
use CoffeeBar\Form\WaiterSelect;
use CoffeeBar\Service\TabCacheService;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\Mvc\MvcEvent;


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
        $tabAggregate = $sm->get('TabAggregate') ;
        $openTabs = $sm->get('OpenTabs') ;
        $em->attachAggregate($tabAggregate) ;
        $em->attachAggregate($openTabs) ;
        
        $cache = $sm->get('TabCache') ;
//        $cache->getCache()->flush() ;
        $cache->setOpenTabs(serialize(new TodoByTab())) ;
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
                'OrderedItems' => 'CoffeeBar\Entity\OrderedItems',
                'OrderedItem' => 'CoffeeBar\Entity\OrderedItem',
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
                    $openTab->setOpenTabs($sm->get('OpenTabs')) ;
                    return $openTab ;
                },
                'PlaceOrderCommand' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $placeOrder = new PlaceOrder() ;
                    $placeOrder->setEventManager($events) ;
                    return $placeOrder ;
                },
                'MarkDrinksServedCommand' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $markDrinksServed = new MarkDrinksServed() ;
                    $markDrinksServed->setEventManager($events) ;
                    return $markDrinksServed ;
                },
                'MarkFoodServedCommand' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $markFoodServed = new MarkFoodServed() ;
                    $markFoodServed->setEventManager($events) ;
                    return $markFoodServed ;
                },
                'TabAggregate' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $cache = $sm->get('Cache\Persistence') ;
                    $tab = new TabAggregate() ;
                    $tab->setEventManager($events) ;
                    $tab->setCache($cache) ;
                    return $tab ;
                },
                'PlaceOrderForm' => function($sm) {
                    $formManager = $sm->get('FormElementManager') ;
                    $form = $formManager->get('CoffeeBar\Form\PlaceOrderForm') ;
                    return $form ;
                },
                'TabCache' => function($sm) {
                    $cacheService = $sm->get('Cache\Persistence') ;
                    $tabCache = new TabCacheService() ;
                    $tabCache->setCache($cacheService) ;
                    return $tabCache ;
                },
                'OpenTabs' => function($sm) {
                    $cache = $sm->get('Cache\Persistence') ;
                    $openTabs = new OpenTabs() ;
                    $openTabs->setCache($cache) ;
                    return $openTabs ;
                },
            ),
        ) ;
    }
}
