<?php

namespace ViewHelper;

/**
 * Description of Module
 *
 * @author haclong
 */
class Module {
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        ) ;
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php' ;
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'afficheTemperature' => 'ViewHelper\View\Helper\Temperature',
                'insereMenuUser' => 'ViewHelper\View\Helper\UserMenu',
            ),
            'factories' => array(
                'listeDernieresInfos' => function($sm) {
                    $service = $sm->getServiceLocator()->get('NewsService') ;
                    $news = $service->getLatestNews() ;
                    return new View\Helper\LastNews($news) ;
                },
            ),
        ) ;
    }
    
    public function getServiceConfig() 
    {
        return array(
            'invokables' => array(
                'NewsService' => 'ViewHelper\Service\NewsService',
            ),
        ) ;
    }
}
