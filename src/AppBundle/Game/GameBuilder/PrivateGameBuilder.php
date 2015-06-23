<?php

namespace AppBundle\Game\GameBuilder;

use AppBundle\Game\PrivateGame;

/**
 * @author dkociuba
 */
class PrivateGameBuilder extends GameBuilder {

    /**
     * @return PrivateGame
     */
    public function build() {
        $game = new PrivateGame($this->config->getValue('boardWidth'), $this->config->getValue('boardHeight'));
        $game->setHashId($this->getUniqHashId());
        return $game;
    }

    private function getUniqHashId() {
        $fullHash = uniqid();
        return substr($fullHash, 0, 5);
    }

}
