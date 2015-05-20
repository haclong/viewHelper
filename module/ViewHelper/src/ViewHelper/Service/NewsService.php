<?php
namespace ViewHelper\Service;

use ViewHelper\Model\News ;
/**
 * Description of NewsService
 *
 * @author haclong
 */
class NewsService {
    public function getLatestNews()
    {
        $news[] = new News('un premier sujet', '18/05/2015', 'un petit sujet pour commencer...', 'moi', 'un peu plus de détails') ;
        $news[] = new News('le chat gris', '07/05/2015', 'parlons du chat', 'lui', 'la nuit, tous les chats sont gris') ;
        $news[] = new News('la musique', '28/04/2015', 'comment jouer de la musique', 'lui', 'les instruments de musique font de la musique') ;
        return $news ;
    }
}
