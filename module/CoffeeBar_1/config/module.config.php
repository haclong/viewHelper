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
                        'child_routes' => array(
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
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
