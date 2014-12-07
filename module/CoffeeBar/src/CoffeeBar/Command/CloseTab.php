<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Entity ;

class CloseTab
{
    protected $id ;
    protected $amountPaid ;

    function getId() {
        return $this->id;
    }

    function getAmountPaid() {
        return $this->amountPaid;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setAmountPaid($amountPaid) {
        $this->amountPaid = $amountPaid;
    }
}

//    public class CloseTab
//    {
//        public Guid Id;
//        public decimal AmountPaid;
//    }
//}
