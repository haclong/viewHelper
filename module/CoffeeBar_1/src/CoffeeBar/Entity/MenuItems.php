<?php

namespace CoffeeBar\Entity ;

use ArrayObject;

class MenuItems extends ArrayObject
{
    public function getById($id)
    {
        $iterator = $this->getIterator() ;
        foreach($iterator as $item)
        {
            if($id == $item->getId())
            {
                return $item ;
            }
        }
    }
}
