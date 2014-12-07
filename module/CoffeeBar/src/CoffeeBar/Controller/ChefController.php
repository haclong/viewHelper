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
            $id = $request->getPost()->get('id') ;

            if(!is_array($request->getPost()->get('prepared'))) {
                $this->flashMessenger()->addErrorMessage('Aucun plat ou boisson n\'a été choisi pour servir');
                return $this->redirect()->toRoute('chef');
            }
            
            $food = array() ;
            foreach($request->getPost()->get('prepared') as $item)
            {
                $groups = explode('_', $item) ;
                $food[] = $groups[2] ;
            }

            if(!empty($food))
            {
                $markPrepared = $this->serviceLocator->get('MarkFoodPreparedCommand') ;
                $markPrepared->markPrepared($id, $food) ;
            }
        }
        return $this->redirect()->toRoute('chef') ;
    }
}
