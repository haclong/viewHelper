<?php

namespace ViewHelper\View\Helper;
use Zend\View\Helper\AbstractHelper ;

/**
 * Description of LastNews
 *
 * @author haclong
 */
class LastNews extends AbstractHelper
{
    protected $lastNews ;

    public function __construct($newsCollection)
    {
        $this->lastNews = $newsCollection ;
    }
    public function __invoke()
    {
        foreach($this->lastNews as $i => $news)
        {
            $lastNews[$i]['title'] = $news->getTitle() ;
            $lastNews[$i]['date'] = $news->getDate() ;
            $lastNews[$i]['summary'] = $news->getSummary() ;
        }
        return $this->getView()->partial('view-helper/plugin/news', array('lastNews' => $lastNews)) ;
    }
}
