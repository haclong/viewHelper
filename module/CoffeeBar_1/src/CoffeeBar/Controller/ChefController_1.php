<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of ChefController
 *
 * @author haclong
 */
class ChefController extends AbstractActionController
{
    public function indexAction()
    {
        $todoList = $this->serviceLocator->get('ChefTodoList') ;
        $list = $todoList->getList() ;
        return array('result' => $list) ;
    }
    
    public function markAction()
    {
        $request = $this->getRequest() ;    
        if($request->isPost()) {
            if(!is_array($request->getPost()->get('prepared'))) {
                $this->flashMessenger()->addErrorMessage('Aucun plat ou boisson n\'a été choisi pour servir');
                return $this->redirect()->toRoute('chef');
            }
            
            $foodPerTab = $this->getPreparedFoodPerTab($request->getPost()->get('prepared')) ;

            if(!empty($foodPerTab))
            {
                $markPrepared = $this->serviceLocator->get('MarkFoodPreparedCommand') ;
                foreach($foodPerTab as $id => $food)
                {
                    $markPrepared->markPrepared($id, $food) ;
                }
            }
        }
        return $this->redirect()->toRoute('chef') ;
    }
    
    protected function getPreparedFoodPerTab($prepared)
    {
        $array = array() ;
        foreach($prepared as $item)
        {
            $groups = explode('_', $item) ;
            $array[$groups[1]][] = $groups[2] ;
        }
        return $array ;
    }
}
