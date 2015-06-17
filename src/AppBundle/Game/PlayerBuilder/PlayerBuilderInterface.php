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
interface PlayerBuilderInterface {
    public function setName($name);
    /**
     * return Player
     */
    public function build();
}
