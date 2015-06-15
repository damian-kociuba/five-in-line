<?php

namespace AppBundle\Game\GameBuilder;

use AppBundle\Game\AI\AIGame;
use Appbundle\ConfigContainer;

/**
 * Description of PublicGameBuilder
 *
 * @author dkociuba
 */
class AIGameBuilder implements GameBuilderInterface {

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
        $game = new AIGame($this->config->getValue('boardWidth'), $this->config->getValue('boardHeight'));
        return $game;
    }

}
