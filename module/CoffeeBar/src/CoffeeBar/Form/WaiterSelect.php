<?php

namespace CoffeeBar\Form ;

use CoffeeBar\Entity\Waiters;
use Zend\Form\Element\Select;

class WaiterSelect extends Select
{
    protected $waiters ;
    
    public function __construct(Waiters $waiters)
    {
        $this->waiters = $waiters ;
    }
    public function init()
    {
//        $waiters = new Waiters ;
        $this->setValueOptions($this->waiters->getArrayCopy()) ;
    }
}
