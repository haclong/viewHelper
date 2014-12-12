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
    // la fonction init va charger l'élément de formulaire customisé
    // les autres éléments de formulaire "standards" peuvent être créé dans le constructeur
    public function init()
    {
        $this->add(array(
            'name' => 'waiter',
            // utiliser la clé définie dans getFormElementConfig dans la classe Module
            'type' => 'WaiterSelect',
            'options' => array(
                'label' => 'Serveur',
            ),
            'attributes' => array(
                // c'est une des classes CSS de Twitter Bootstrap
                'class' => 'form-control',
            ),
        )) ;
    }

    public function __construct()
    {
        parent::__construct('opentab') ;
        
        $this->setAttribute('method', 'post')
             ->setHydrator(new ArraySerializable()) ;
        
        // le champ id est un id unique (guid) caché
        // il sera généré automatiquement dans la vue
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
            'attributes' => array(
                'value' => 'Open',
                'class' => 'btn btn-default',
            ),
        )) ;
    }
}