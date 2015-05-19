<?php

namespace ViewHelper\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of IndexController
 *
 * @author haclong
 */
class IndexController extends AbstractActionController 
{
    public function temperatureAction()
    {
        $celsius = $this->params()->fromRoute('celsius') ;
        return array('temperature' => $celsius) ;
    }
}