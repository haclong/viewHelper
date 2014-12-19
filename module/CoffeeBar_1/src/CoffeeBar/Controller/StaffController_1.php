<?php

namespace CoffeeBar\Controller ;

use Zend\Mvc\Controller\AbstractActionController;

class StaffController extends AbstractActionController
{
    public function toDoAction()
    {
        $waiter = $this->params()->fromRoute('name');
        $openTabs = $this->serviceLocator->get('OpenTabs') ;
        $list = $openTabs->todoListForWaiter($waiter) ;
        return array('result' => $list, 'waiter' => $waiter) ;
    }
    
    public function indexAction()
    {
        $waiters = $this->serviceLocator->get('CoffeeBarEntity\Waiters') ;
        return array('result' => $waiters) ;
    }
    
    public function markAction()
    {
        $request = $this->getRequest() ;    
        if($request->isPost()) {
            $id = $request->getPost()->get('id') ;
            $waiter = $request->getPost()->get('waiter') ;
        
            if(!is_array($request->getPost()->get('served'))) {
                $this->flashMessenger()->addErrorMessage('Aucun plat ou boisson n\'a été choisi pour servir');
                return $this->redirect()->toRoute('staff/todo', array('name' => $waiter));
            } 
            
            $menuNumbers = array() ;
            foreach($request->getPost()->get('served') as $item)
            {
                $groups = explode('_', $item) ;
                $menuNumbers[] = $groups[1] ;
            }
            $this->markDrinksServed($id, $menuNumbers) ;
            $this->markFoodServed($id, $menuNumbers) ;

            return $this->redirect()->toRoute('staff/todo', array('name' => $waiter));
        }
    }

    protected function markDrinksServed($id, array $menuNumbers)
    {
        $menu = $this->serviceLocator->get('CoffeeBarEntity\MenuItems') ;
        $openTabs = $this->serviceLocator->get('OpenTabs') ;
        $tabId = $openTabs->tabIdForTable($id) ;
        
        $drinks = array() ;
        foreach($menuNumbers as $nb)
        {
            if($menu->getById($nb)->getIsDrink())
            {
                $drinks[] = $nb ; 
            }
        }
        
        if(!empty($drinks))
        {
            $markServed = $this->serviceLocator->get('MarkDrinksServedCommand') ;
            $markServed->markServed($tabId, $drinks) ;
        }
    }
    
    protected function markFoodServed($id, array $menuNumbers)
    {
        $menu = $this->serviceLocator->get('CoffeeBarEntity\MenuItems') ;
        $openTabs = $this->serviceLocator->get('OpenTabs') ;
        $tabId = $openTabs->tabIdForTable($id) ;
        
        $food = array() ;
        foreach($menuNumbers as $nb)
        {
            if(!$menu->getById($nb)->getIsDrink())
            {
                $food[] = $nb ; 
            }
        }

        if(!empty($food))
        {
            $markServed = $this->serviceLocator->get('MarkFoodServedCommand') ;
            $markServed->markServed($tabId, $food) ;
        }
    }
}