<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity\OpenTabs ;

use ArrayObject;

class ItemsArray extends ArrayObject 
{
    public function getKeyByMenuNumber($menuNumber)
    {
        $iterator = $this->getIterator() ;
        
        foreach($iterator as $key => $value)
        {
            if($value->getMenuNumber() == $menuNumber) {
                return $key ;
            }
        }
    }
    
    public function addItem($item)
    {
        $this->offsetSet(NULL, $item) ;
    }
}