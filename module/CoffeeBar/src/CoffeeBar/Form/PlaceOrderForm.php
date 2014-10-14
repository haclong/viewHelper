<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Form ;

use CoffeeBar\Entity\OrderModel;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ArraySerializable;

class PlaceOrderForm extends Form
{
    public function init()
    {
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'items',
            'options' => array(
                'label' => 'Commandez vos plats',
                'count' => 2,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'CoffeeBar\Form\MenuItemFieldset',
                ),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
    }
    public function __construct()
    {
        parent::__construct('order') ;
        
        $this->setAttribute('method', 'post')
             ->setHydrator(new ArraySerializable(false))
             ->setObject(new OrderModel) ;
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        )) ;
        
        $this->add(new Csrf('security')) ;
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Place order',
                'class' => 'btn btn-default',
            ),
        )) ;
    }
}