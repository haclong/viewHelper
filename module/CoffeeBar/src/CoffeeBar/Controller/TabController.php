<?php

namespace CoffeeBar\Controller ;

use Zend\Mvc\Controller\AbstractActionController;

class TabController extends AbstractActionController
{
    public function openAction()
    {
        $form = $this->serviceLocator->get('OpenTabForm') ;
        $request = $this->getRequest() ;

        if($request->isPost()) {
            $form->setData($request->getPost()) ;
            
//var_dump($request->getPost()) ;
            if($form->isValid()) {
//var_dump($form->getObject()) ;
                $openTab = $form->getObject() ;
//                return $this->redirect()->toRoute('tab/order', array('id' => $openTab->getTableNumber()));
            }
        }
        
        $result['form'] = $form ;
        return array('result' => $result) ;
    }
    
    public function orderAction()
    {
        $form = $this->serviceLocator->get('PlaceOrderForm') ;
        $request = $this->getRequest() ;

        if ($id = (int) $this->params()->fromRoute('id')) {
            $form->get('id')->setValue($id) ;
        } elseif($request->isPost()) {
            $form->setData($request->getPost()) ;
            
//var_dump($request->getPost()) ;
            if($form->isValid()) {
//var_dump($openTab) ;
                var_dump($form->getObject()) ;
            }
        } else {
            return $this->redirect()->toRoute('tab/open');
        }
        
        $result['form'] = $form ;
        return array('result' => $result) ;
    }

    public function closeAction()
    {
        return array('result' => '') ;
    }
    
    public function listOpenedAction()
    {
        return array('result' => '') ;
    }
}