<?php

namespace AppBundle\Game;

use AppBundle\Game\GameBuilder\PrivateGameBuilder;
use AppBundle\Game\GameBuilder\PublicGameBuilder;
use AppBundle\Game\GameBuilder\AIGameBuilder;
use AppBundle\Game\GameBuilder\GameBuilder;
use AppBundle\Game\Player;
use AppBundle\ConfigContainer;
use AppBundle\Game\Game;

/**
 * Description of GameBuilder
 *
 * @author dkociuba
 */
class GameBuilderSupervisor {

    /**
     *
     * @var ConfigContainer
     */
    private $config;

    /**
     *
     * @var Player
     */
    private $creator;

    public function __construct(ConfigContainer $config) {
        $this->config = $config;
    }

    public function setCreator(Player $creator) {
        $this->creator = $creator;
    }

    /**
     * 
     * @param GameBuilder $builder
     * @return type
     */
    public function createGame(GameBuilder $builder) {

        $builder->setConfig($this->config);
        $game = $builder->build();

        $game->addPlayer($this->creator);
        return $game;
    }

}
