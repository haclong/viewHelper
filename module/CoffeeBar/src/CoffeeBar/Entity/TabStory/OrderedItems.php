<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity\TabStory ;

use ArrayObject;

class OrderedItems extends ArrayObject
{
    public function getDrinkableItems()
    {
        $iterator = $this->getIterator() ;
        $items = new OrderedItems() ;
        foreach($iterator as $item)
        {
            if($item->getIsDrink())
            {
                $items->offsetSet(NULL, $item) ;
            }
        }
        return $items ;
    }
    
    public function getEatableItems()
    {
        $iterator = $this->getIterator() ;
        $items = new OrderedItems() ;
        foreach($iterator as $item)
        {
            if(!$item->getIsDrink())
            {
                $items->offsetSet(NULL, $item) ;
            }
        }
        return $items ;
    }
    
    public function getKeyById($id)
    {
        $iterator = $this->getIterator() ;
        
        foreach($iterator as $key => $value)
        {
            if($value->getId() == $id) {
                return $key ;
            }
        }
    }
}