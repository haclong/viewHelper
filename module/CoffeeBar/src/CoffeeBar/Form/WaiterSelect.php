<?php

namespace CoffeeBar\Form ;

use CoffeeBar\Entity\Waiters;
use Zend\Form\Element\Select;

class WaiterSelect extends Select
{
    protected $waiters ;
    
    // dans le constructeur, on injecte la liste des serveurs (objet CoffeeBar\Entity\Waiters)
    // ainsi dans l'objet CoffeeBar\Form\WaiterSelect, on peut l'utiliser comme on le souhaite
    public function __construct(Waiters $waiters)
    {
        $this->waiters = $waiters ;
    }
    
    // dans la méthode init(), on récupère la liste des serveurs (de l'objet Waiters)
    // on définit la liste des serveurs comme la liste des options de l'élément Select
    // $this->setValueOptions() est une méthode quei fait partie de l'objet Select
    // la méthode ArrayObject::getArrayCopy() prendre l'objet ArrayObject tel quel
    // et le retourne sous forme de tableau
    public function init()
    {
        $this->setValueOptions($this->waiters->getArrayCopy()) ;
    }
}
