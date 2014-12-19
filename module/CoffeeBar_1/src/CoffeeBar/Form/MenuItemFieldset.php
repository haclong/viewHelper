<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Form ;

use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;
use CoffeeBar\Entity\TabStory\OrderItem ;

class MenuItemFieldset extends Fieldset
{
    public function __construct()
    {
        $this->setHydrator(new ClassMethods()) ;
        $this->setObject(new OrderItem()) ;
    }
}