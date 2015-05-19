<?php

namespace ViewHelper\View\Helper;
use Zend\View\Helper\AbstractHelper ;

/**
 * Description of UserMenu
 *
 * @author haclong
 */
class UserMenu extends AbstractHelper
{
    public function __invoke()
    {
        return $this->getView()->render('view-helper/plugin/user') ;
    }
}