<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Form ;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ArraySerializable;

class OpenTabForm extends Form
{
    public function init()
    {
        $this->add(array(
            'name' => 'waiter',
            'type' => 'WaiterSelect',
            'options' => array(
                'label' => 'Serveur',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        )) ;
    }

    public function __construct()
    {
        parent::__construct('opentab') ;
        
        $this->setAttribute('method', 'post')
             ->setHydrator(new ArraySerializable()) ;
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        )) ;
        $this->add(array(
            'name' => 'tableNumber',
            'options' => array(
                'label' => 'Numéro de la table',
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
        )) ;
        
        $this->add(new Csrf('security')) ;
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'options' => array(
//                'label' => 'Open',
            ),
            'attributes' => array(
                'value' => 'Open',
                'class' => 'btn btn-default',
            ),
        )) ;
    }
    
    public function getInputFilterSpecification()
    {
        return array(
            'table' => array(
                'required' => true,
            ),
        ) ;
    }
}