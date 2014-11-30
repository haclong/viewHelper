<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
//            'coffeebar' => array(
//                'type'    => 'Literal',
//                'options' => array(
//                    'route'    => '/coffeebar',
//                ),
//                'child_routes' => array(
                    'home' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/',
                            'defaults' => array(
                                'controller'    => 'CoffeeBarController\Index',
                                'action'        => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'tab' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/tab',
                        ),
                        'child_routes' => array(
                            'open' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/open',
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Tab',
                                        'action'        => 'open',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'order' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/order[/:id]',
                                    'constraints' => array(
                                        'id' => '[a-zA-Z0-9_-]+',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Tab',
                                        'action'        => 'order',
                                    ),
                                ),
                            ),
                            'close' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/close/[:id]',
                                    'constraints' => array(
                                        'id' => '[a-zA-Z0-9_-]+',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Tab',
                                        'action'        => 'close',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'opened' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/opened',
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Tab',
                                        'action'        => 'listOpened',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'status' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/status/[:id]',
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Tab',
                                        'action'        => 'status',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'staff' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/staff',
                        ),
//                        'may_terminate' => true,
                        'child_routes' => array(
                            'list' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/waiters',
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Staff',
                                        'action'        => 'waiterList',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'todo' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/[:name]',
                                    'constraints' => array(
                                        'name' => 'waiters{0}|[a-zA-Z]+',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Staff',
                                        'action'        => 'waiterToDo',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'cook' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/chef',
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Staff',
                                        'action'        => 'chefToDo',
                                    ),
                                ),
                            ),
                        ),
                    ),
//                ),
//            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'CoffeeBarController\Index' => 'CoffeeBar\Controller\IndexController',
            'CoffeeBarController\Staff' => 'CoffeeBar\Controller\StaffController',
            'CoffeeBarController\Tab'   => 'CoffeeBar\Controller\TabController',
        ),
        'factories' => array(
//            'CoffeeBarController\Tab' => 'CoffeeBar\Factory\TabControllerFactory', 
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'MenuItemFormCollection' => 'CoffeeBar\Form\Helper\MenuItemFormCollection',
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Open new tab',
                'route' => 'tab/open',
            ),
            array(
                'label' => 'Opened tabs',
                'route' => 'tab/opened',
            ),
            array(
                'label' => 'Staff',
                'route' => 'staff/list',
            ),
            array(
                'label' => 'Chef\'s todo',
                'route' => 'staff/cook',
            ),
        ),
    ),
);
