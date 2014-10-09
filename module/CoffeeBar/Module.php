<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CoffeeBar;

use CoffeeBar\Entity\OpenTab;
use CoffeeBar\Form\MenuSelect;
use CoffeeBar\Form\WaiterSelect;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

class Module implements FormElementProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
                    $waiters = $serviceLocator->get('CoffeeBar\Entity\Waiters') ;
                    $select = new WaiterSelect($waiters) ;
                    return $select ;
                },
                'MenuSelect' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator() ;
                    $menus = $serviceLocator->get('CoffeeBar\Entity\MenuItems') ;
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
                'CoffeeBar\Entity\Waiters' => 'CoffeeBar\Entity\Waiters',
                'CoffeeBar\Entity\MenuItems' => 'CoffeeBar\Entity\MenuItems',
                'CoffeeBar\Entity\TabAggregate' => 'CoffeeBar\Entity\TabAggregate',
            ),
            'factories' => array(
                'OpenTabForm' => function($sm) {
                    $formManager = $sm->get('FormElementManager') ;
                    $form = $formManager->get('CoffeeBar\Form\OpenTabForm') ;
                    $form->setObject($sm->get('OpenTabEntity')) ;
                    return $form ;
                },
                'OpenTabEntity' => function($sm) {
                    $tab = $sm->get('CoffeeBar\Entity\TabAggregate') ;
                    $openTab = new OpenTab() ;
                    $openTab->setEventManager($tab->events()) ;
                    return $openTab ;
                },
            ),
        ) ;
    }
}
