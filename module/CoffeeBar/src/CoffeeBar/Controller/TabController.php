<?php

namespace CoffeeBar\Controller ;

use CoffeeBar\Exception\TabAlreadyOpened;
use Zend\Mvc\Controller\AbstractActionController;

class TabController extends AbstractActionController
{
    public function openAction()
    {
        $form = $this->serviceLocator->get('OpenTabForm') ;
        $request = $this->getRequest() ;

        if($request->isPost()) {
            $form->setData($request->getPost()) ;
            
            try {
                $form->isValid() ;
                $openTab = $form->getObject() ;
                return $this->redirect()->toRoute('tab/order', array('id' => $openTab->getTableNumber()));
            } catch (TabAlreadyOpened $ex) {
                $this->flashMessenger()->addErrorMessage($ex->getMessage());
                return $this->redirect()->toRoute('tab/open');
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
            
var_dump($request->getPost()) ;
            if($form->isValid()) {
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
        $cache = $this->serviceLocator->get('TabCache') ;
        $openTabs = $cache->getOpenTabs() ;
        return array('result' => $openTabs) ;
    }
    
    public function statusAction()
    {
        $openTabs = $this->serviceLocator->get('OpenTabs') ;
        $status = $openTabs->tabForTable($this->params()->fromRoute('id')) ;
        return array('result' => $status) ;
    }
}
