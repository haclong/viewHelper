<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ViewHelper\IndexController' => 'ViewHelper\Controller\IndexController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'temperature' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/temperature/[:celsius]',
                    'constraints' => array(
                        'celsius' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ViewHelper\IndexController',
                        'action' => 'temperature',
                        'celsius' => 37,
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
    
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Temperature',
                'route' => 'temperature',
                'order' => 100,
            ),
       ),
    ),
);