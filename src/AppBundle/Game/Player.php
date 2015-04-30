<?php

namespace AppBundle\Game;

/**
 * Description of Player
 *
 * @author dkociuba
 */
class Player {
    private $name;
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
}
