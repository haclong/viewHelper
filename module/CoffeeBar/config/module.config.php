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
            /**
             * on écrase la route 'home' qui est définie par défaut dans Application
             * voir le fichier /module/Application/config/module.config.php
             * cette adresse URL mène à la route http://coffeebar.home/
             */
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
                        /**
                         * cette route ne correspond à aucune page
                         * notez l'absence d'options 'defaults' parce qu'il n'y a
                         * pas de pages valides à cette adresse http://coffeebar.home/tab
                         * cette adresse retourne une erreur 404
                         * vous pouvez également décider de définir un contrôleur 
                         * et une action par défaut pour éviter l'erreur 404
                         */
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/tab',
                        ),
                        'child_routes' => array(
                            /**
                             * cette URL (http://coffeebar.com/tab/open) mène à cette route
                             */
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
                            /**
                             * cette URL : http://coffeebar.home/tab/order mène à cette route
                             */
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
                            /**
                             * cette URL : http://coffeebar.home/tab/close/{$id} mène à cette route
                             */
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
                            /**
                             * cette URL : http://coffeebar.home/tab/opened mène à cette route
                             */
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
                            /**
                             * cette URL : http://coffeebar.home/tab/status mène à cette route
                             */
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
                    /**
                     * cette URL : http://coffeebar.home/flush mène à cette route
                     */
                    'flush' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/flush',
                            'defaults' => array(
                                'controller'  => 'CoffeeBarController\Index',
                                'action'      => 'flush',
                            ),
                        ),
                    ),
                    /**
                     * cette URL : http://coffeebar.home/staff mène à cette route
                     */
                    'staff' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/staff',
                            'defaults' => array(
                                'controller' => 'CoffeeBarController\Staff',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            /**
                             * cette URL : http://coffeebar.home/staff/{$waiter} mène à cette route
                             */
                            'todo' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/[:name]',
                                    'constraints' => array(
                                        'name' => '[a-zA-Z]+',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'CoffeeBarController\Staff',
                                        'action'        => 'toDo',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'markserved' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/mark',
                                    'defaults' => array(
                                        'controller' => 'CoffeeBarController\Staff',
                                        'action'     => 'mark',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    /**
                     * cette URL : http://coffeebar.home/chef mène à cette route
                     */
                    'chef' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/chef',
                            'defaults' => array(
                                'controller' => 'CoffeeBarController\Chef',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            /**
                             * cette URL : http:///coffeebar.home/chef/mark mène à cette route
                             */
                            'markprepared' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/mark',
                                    'defaults' => array(
                                        'controller' => 'CoffeeBarController\Chef',
                                        'action' => 'mark',
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
            'CoffeeBarController\Chef'  => 'CoffeeBar\Controller\ChefController',
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
                'route' => 'tab/open', // utiliser les clés du tableau $router
            ),
            array(
                'label' => 'Opened tabs',
                'route' => 'tab/opened',
            ),
            array(
                'label' => 'Staff',
                'route' => 'staff',
            ),
            array(
                'label' => 'Chef\'s todo',
                'route' => 'chef',
            ),
            array(
                'label' => 'Flush cache',
                'route' => 'flush',
            ),
        ),
    ),
);
