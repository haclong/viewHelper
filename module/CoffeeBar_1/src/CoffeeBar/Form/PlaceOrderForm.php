<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Form ;

use CoffeeBar\Entity\TabStory\OrderModel;
use Zend\Stdlib\Hydrator\ArraySerializable;

class PlaceOrderForm extends Form
{
    public function __construct()
    {
             ->setHydrator(new ArraySerializable())
             ->setObject(new OrderModel) ;
}