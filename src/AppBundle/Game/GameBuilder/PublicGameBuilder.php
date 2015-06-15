<?php

namespace AppBundle\Game\GameBuilder;

use AppBundle\Game\PublicGame;

/**
 * Description of PublicGameBuilder
 *
 * @author dkociuba
 */
class PublicGameBuilder implements GameBuilderInterface {

    /**
     * @var ConfigContainer
     */
    private $config;

    public function setConfig(ConfigContainer $config) {
        $this->config = $config;
    }

    /**
     * @return PublicGame
     */
    public function build() {
        $game = new PublicGame($this->config->getValue('boardWidth'), $this->config->getValue('boardHeight'));
        return $game;
    }

}
