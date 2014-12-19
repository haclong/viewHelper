<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CoffeeBar;

use CoffeeBar\Command\CloseTab;
use CoffeeBar\Command\MarkDrinksServed;
use CoffeeBar\Command\MarkFoodPrepared;
use CoffeeBar\Command\MarkFoodServed;
use CoffeeBar\Command\PlaceOrder;
use CoffeeBar\Form\CloseTabForm;
use CoffeeBar\Form\MenuSelect;
use CoffeeBar\Service\ChefTodoList;


class Module implements FormElementProviderInterface
{
    public function onBootstrap(MvcEvent $event)
    {
//        $sm = $event->getApplication()->getServiceManager() ;
//        $em = $sm->get('TabEventManager');
        $em->attachAggregate($sm->get('ChefTodoList')) ;
    }
    

    // on charge le service manager
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'OrderedItems' => 'CoffeeBar\Entity\TabStory\OrderedItems',
                'OrderedItem' => 'CoffeeBar\Entity\TabStory\OrderedItem',
            ),
            'factories' => array(
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
                'MarkFoodPreparedCommand' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $markFoodPrepared = new MarkFoodPrepared() ;
                    $markFoodPrepared->setEventManager($events) ;
                    return $markFoodPrepared ;
                },
                'MarkFoodServedCommand' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $markFoodServed = new MarkFoodServed() ;
                    $markFoodServed->setEventManager($events) ;
                    return $markFoodServed ;
                },
                'CloseTabForm' => function($sm) {
                    $form = new CloseTabForm() ;
                    $form->setObject($sm->get('CloseTabCommand')) ;
                    return $form ;
                },
                'CloseTabCommand' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $closeTab = new CloseTab() ;
                    $closeTab->setEventManager($events) ;
                    return $closeTab ;
                },
                'ChefTodoList' => function($sm) {
                    $cache = $sm->get('Cache\Persistence') ;
                    $todoList = new ChefTodoList() ;
                    $todoList->setCache($cache) ;
                    return $todoList ;
                },
            ),
        ) ;
    }
}
