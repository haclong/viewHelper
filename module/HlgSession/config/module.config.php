<?php

return array(
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent',
        ),
    ),
);
