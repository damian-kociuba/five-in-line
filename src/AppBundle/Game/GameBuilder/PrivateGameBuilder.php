<?php

namespace AppBundle\Game\GameBuilder;

use AppBundle\Game\PrivateGame;
use Appbundle\ConfigContainer;

/**
 * Description of PrivateGameBuilder
 *
 * @author dkociuba
 */
class PrivateGameBuilder implements GameBuilderInterface {

    /**
     * @var ConfigContainer
     */
    private $config;

    public function setConfig(ConfigContainer $config) {
        $this->config = $config;
    }

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
