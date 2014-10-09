<?php

namespace CoffeeBar\Controller ;

use Zend\Mvc\Controller\AbstractActionController;
//use Zend\EventManager\EventManager ;

class TabController extends AbstractActionController
{
//    public function __construct(EventManager $eventManager)
//    {
//        $this->eventsManager = $eventManager ;
//    }

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
//                $this->eventsManager->trigger('openTab', $this, array($form->getObject())) ;
                return $this->redirect()->toRoute('tab/order', array('id' => $openTab->getTableNumber()));
            }
        }
        
        $result['form'] = $form ;
        return array('result' => $result) ;
    }
    
    public function orderAction()
    {
        $formManager = $this->serviceLocator->get('FormElementManager') ;
        $form = $formManager->get('CoffeeBar\Form\PlaceOrderForm') ;
        $request = $this->getRequest() ;

        if ($id = (int) $this->params()->fromRoute('id')) {
            $orderId = $form->get('id') ;
//        var_dump($request->isGet()) ;
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