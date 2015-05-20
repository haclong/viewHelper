<?php

namespace ViewHelper\Model;

/**
 * Description of News
 *
 * @author haclong
 */
class News {
    protected $title ;
    protected $date ;
    protected $summary ;
    protected $author ;
    protected $body ;
    
    function __construct($title, $date, $summary, $author, $body) {
        $this->title = $title;
        $this->date = $date;
        $this->summary = $summary;
        $this->author = $author;
        $this->body = $body;
    }
    
    function getTitle() {
        return $this->title;
    }

    function getDate() {
        return $this->date;
    }

    function getSummary() {
        return $this->summary;
    }

    function getAuthor() {
        return $this->author;
    }

    function getBody() {
        return $this->body;
    }
}
