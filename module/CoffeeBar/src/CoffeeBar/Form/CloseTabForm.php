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

class CloseTabForm extends Form
{
    public function __construct()
    {
        parent::__construct('closetab') ;
        
        $this->setAttribute('method', 'post')
             ->setHydrator(new ArraySerializable()) ;
        
        // le champ id est un id unique (guid) caché
        // il sera généré automatiquement dans la vue
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        )) ;

        $this->add(array(
            'name' => 'amountPaid',
            'options' => array(
                'label' => 'Encaissement',
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
            'attributes' => array(
                'value' => 'Encaisser',
                'class' => 'btn btn-default',
            ),
        )) ;
    }
}