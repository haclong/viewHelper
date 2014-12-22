<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CoffeeBar;

use CoffeeBar\Command\CloseTab;
use CoffeeBar\Form\CloseTabForm;


class Module implements FormElementProviderInterface
{
    // on charge le service manager
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CloseTabForm' => function($sm) {
                    $form = new CloseTabForm() ;
                    $form->setObject($sm->get('CloseTabCommand')) ;
                    return $form ;
                },
                'CloseTabCommand' => function($sm) {
                    $events = $sm->get('TabEventManager') ;
                    $closeTab = new CloseTab() ;
                    $closeTab->setEventManager($events) ;
                    return $closeTab ;
                },
            ),
        ) ;
    }
}
