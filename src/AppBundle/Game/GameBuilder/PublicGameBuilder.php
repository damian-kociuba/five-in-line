<?php

namespace AppBundle\Game\GameBuilder;

use AppBundle\Game\PublicGame;

/**
 * @author dkociuba
 */
class PublicGameBuilder extends GameBuilder {

    /**
     * @return PublicGame
     */
    public function build() {
        $game = new PublicGame($this->config->getValue('boardWidth'), $this->config->getValue('boardHeight'));
        return $game;
    }

}
