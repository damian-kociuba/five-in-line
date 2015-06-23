<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Game\PlayerBuilder;

use AppBundle\Game\Player;

/**
 *
 * @author dkociuba
 */
abstract class PlayerBuilder {

    protected $name;
    
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return Player
     */
    public abstract function build();
}
