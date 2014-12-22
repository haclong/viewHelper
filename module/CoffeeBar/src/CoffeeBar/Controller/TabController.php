<?php

namespace CoffeeBar\Controller ;

use CoffeeBar\Entity\TabStory\OrderModel;
use CoffeeBar\Exception\MustPayEnough;
use CoffeeBar\Exception\TabAlreadyClosed;
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
            
            $posted = $request->getPost() ;

            $openTabs = $this->serviceLocator->get('OpenTabs') ;

            try {
                if($openTabs->isTableActive($posted['tableNumber'])) {
                    throw new TabAlreadyOpened('Tab is already opened') ;
                }
            } catch (TabAlreadyOpened $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
                return $this->redirect()->toRoute('tab/open');
            }
            
            if($form->isValid()) {
                    $openTab = $form->getObject() ;
                    return $this->redirect()->toRoute('tab/order', array('id' => $openTab->getTableNumber()));
            }
        }

        $result['form'] = $form ;
        return array('result' => $result) ;
    }
    
    public function orderAction()
    {
        // utiliser la clé déclarée dans le Service Manager (classe Module)
        $form = $this->serviceLocator->get('PlaceOrderForm') ;
        $request = $this->getRequest() ;

        // vérifier si on connait le numéro de la table pour laquelle on passe commande
        if ($id = (int) $this->params()->fromRoute('id')) {
            $form->get('id')->setValue($id) ;
        // sinon, vérifier si le formulaire a été posté
        } elseif($request->isPost()) {
            $form->setData($request->getPost()) ;
            if($form->isValid()) {
                $orderModel = $form->getObject() ;
                $tableNumber = $orderModel->getId() ;
                $openTabs = $this->serviceLocator->get('OpenTabs') ;
                $placeOrder = $this->serviceLocator->get('PlaceOrderCommand') ;
                $items = $this->assignOrderedItems($orderModel) ;
                $placeOrder->placeOrder($openTabs->tabIdForTable($tableNumber), $items) ;
                return $this->redirect()->toRoute('tab/status', array('id' => $tableNumber));
            }
        // si on ne sait pas pour quelle table on va passer commande, retourner à la page 'Ouvrir une commande'
        } else {
            return $this->redirect()->toRoute('tab/open');
        }
        
        $result['form'] = $form ;
        return array('result' => $result) ;
    }

    public function closeAction()
    {
        $openTabs = $this->serviceLocator->get('OpenTabs') ;

        $form = $this->serviceLocator->get('CloseTabForm') ;
        $request = $this->getRequest() ;
        $id = (int) $this->params()->fromRoute('id') ;

        // vérifier si on connait le numéro de la table pour laquelle on passe commande
        if (isset($id)) {
            // vérifier si le formulaire a été posté
            if($request->isPost()) {
                $form->setData($request->getPost()) ;
            
                try {
                    $form->isValid() ;
                    $this->flashMessenger()->addMessage('La note a été fermée avec succès');
                    return $this->redirect()->toRoute('tab/opened');
                } catch (MustPayEnough $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    return $this->redirect()->toRoute('tab/close', array('id' => $id));
                } catch (TabAlreadyClosed $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage()) ;
                    return $this->redirect()->toRoute('tab/opened') ;
                }
            }

            $status = $openTabs->invoiceForTable($id) ;

            try {
                if($status->hasUnservedItems())
                {
                    throw new TabHasUnservedItem('Il reste des éléments commandés pour cette table') ;
                }
            } catch (TabHasUnservedItem $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
                return $this->redirect()->toRoute('tab/status', array('id' => $id));
            }

            $form->get('id')->setValue($openTabs->tabIdForTable($id)) ;
        // si on ne sait pas pour quelle table on va passer commande, retourner à la page 'Ouvrir une commande'
        } else {
            return $this->redirect()->toRoute('tab/opened');
        }

        $result['status'] = $status ;
        $result['form'] = $form ;
        return array('result' => $result) ;
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
        $status = $openTabs->statusForTable($this->params()->fromRoute('id')) ;
        return array('result' => $status) ;
    }
    
    protected function assignOrderedItems(OrderModel $model)
    {
        $items = $this->serviceLocator->get('OrderedItems') ;
        $menu = $this->serviceLocator->get('CoffeeBarEntity\MenuItems') ;
        foreach($model->getItems() as $item)
        {
            for($i = 0; $i < $item->getNumber(); $i++)
            {
                $orderedItem = clone $this->serviceLocator->get('OrderedItem') ;
                $orderedItem->setId($item->getId()) ;
                $orderedItem->setDescription($menu->getById($item->getId())->getDescription()) ;
                $orderedItem->setPrice($menu->getById($item->getId())->getPrice()) ;
                $orderedItem->setIsDrink($menu->getById($item->getId())->getIsDrink()) ;
                $items->offsetSet(NULL, $orderedItem) ;
            }
        }
        return $items ;
    }
}