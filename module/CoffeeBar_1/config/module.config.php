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
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'CoffeeBarController\Staff' => 'CoffeeBar\Controller\StaffController',
            'CoffeeBarController\Chef'  => 'CoffeeBar\Controller\ChefController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Staff',
                'route' => 'staff',
            ),
            array(
                'label' => 'Chef\'s todo',
                'route' => 'chef',
            ),
        ),
    ),
);
