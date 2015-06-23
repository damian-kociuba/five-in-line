<?php

namespace AppBundle\Game\GameBuilder;

use AppBundle\Game\AI\AIGame;

/**
 * Description of PublicGameBuilder
 *
 * @author dkociuba
 */
class AIGameBuilder extends GameBuilder {

    /**
     * @return PublicGame
     */
    public function build() {
        $game = new AIGame($this->config->getValue('boardWidth'), $this->config->getValue('boardHeight'));
        return $game;
    }

}
