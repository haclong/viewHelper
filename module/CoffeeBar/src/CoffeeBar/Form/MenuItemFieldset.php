<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Form ;

use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;
use CoffeeBar\Entity\OrderItem ;

class MenuItemFieldset extends Fieldset
{
    public function init()
    {
        $this->add(array(
            'name' => 'id',
            'type' => 'MenuSelect',
            'options' => array(
                'label' => ' ',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        )) ;
    }

    public function __construct()
    {
        parent::__construct('menuItems') ;
        
        $this->setHydrator(new ClassMethods) ;
        $this->setObject(new OrderItem()) ;
        
        $this->add(array(
            'name' => 'number',
            'options' => array(
                'label' => ' ',
            ),
            'attributes' => array(
                'class' => 'form-control text-right',
                'size' => 4,
            ),
        )) ;
    }
}