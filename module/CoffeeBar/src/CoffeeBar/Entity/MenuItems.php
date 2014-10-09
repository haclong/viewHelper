<?php

namespace CoffeeBar\Entity ;

use ArrayObject;

class MenuItems extends ArrayObject
{
    public function __construct(Array $array=null)
    {
        $array = array() ;
        $i = 0 ;
        $array[] = new MenuItem($i++, 'Thé vert', 3.75, true) ;
        $array[] = new MenuItem($i++, 'Café', 2.55, true) ;
        $array[] = new MenuItem($i++, 'Limonade', 4.05, true) ;
        $array[] = new MenuItem($i++, 'Soda', 4.20, true) ;
        $array[] = new MenuItem($i++, 'Bière', 4.75, true) ;
        $array[] = new MenuItem($i++, 'Frites', 5.25) ;
        $array[] = new MenuItem($i++, 'Pizza', 9.80) ;
        $array[] = new MenuItem($i++, 'Saucisses Frites', 7.75) ;
        $array[] = new MenuItem($i++, 'Hot Dog', 7.00) ;
        $array[] = new MenuItem($i++, 'Quiche', 6.65) ;
        parent::__construct($array) ;
    }
    
    public function getSelectValues()
    {
        $array = array() ;
        
        $iterator = $this->getIterator() ;
        foreach($iterator as $item)
        {
            $array[$item->getId()] = $item->getDescription() ;
        }
        return $array ;
    }
}
