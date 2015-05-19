<?php

namespace ViewHelper\View\Helper;
use Zend\View\Helper\AbstractHelper ;

/**
 * Description of Temperature
 *
 * @author haclong
 */
class Temperature extends AbstractHelper
{
    public function __invoke($temperature, $useFahrenheit=false)
    {
        // pseudo code
        if($useFahrenheit == true)
        {
            return $this->convertCToF($temperature) . ' °F' ;
        }
        return $temperature . ' °C' ;
    }
    
    protected function convertCToF($temperature)
    {
        return ($temperature * 9/5) + 32 ;
    }
}
