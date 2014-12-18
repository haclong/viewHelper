<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Exception ;

use Exception;

/*
 *  You can not mark a food as served if it wasn't ordered in the first place
 *  You can not mark a food as served if you already served it
 */
class FoodNotPrepared extends Exception
{
    
}