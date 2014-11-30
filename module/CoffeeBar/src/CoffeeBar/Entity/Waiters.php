<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

use ArrayObject ;

class Waiters extends ArrayObject
{
    public function __construct(Array $array=null)
    {
        $array = array('paul' => 'Paul', 
                       'john' => 'John', 
                       'melissa' => 'Melissa', 
                       'julie' => 'Julie', 
                       'michael' => 'Michael') ;
        parent::__construct($array) ;
    }
}
