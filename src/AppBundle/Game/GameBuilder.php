<?php

namespace AppBundle\Game;

use AppBundle\Game\GameBuilder\PrivateGameBuilder;
use AppBundle\Game\GameBuilder\PublicGameBuilder;
use AppBundle\Game\GameBuilder\AIGameBuilder;
use AppBundle\Game\GameBuilder\GameBuilderInterface;
use AppBundle\Game\Player;
use AppBundle\ConfigContainer;
use AppBundle\Game\Game;

/**
 * Description of GameBuilder
 *
 * @author dkociuba
 */
class GameBuilder {

    const PRIVATE_GAME = 1;
    const PUBLIC_GAME = 2;
    const AI_GAME = 3;

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
     * @param type $type
     * @return Game
     */
    public function createGame($type) {
        $builder = $this->getBuilder($type);

        $builder->setConfig($this->config);
        $game = $builder->build();

        $game->addPlayer($this->creator);
        return $game;
    }

    /**
     * 
     * @param int $type
     * @return GameBuilderInterface
     * @throws \Exception
     */
    private function getBuilder($type) {
        switch ($type) {
            case self::PRIVATE_GAME : return new PrivateGameBuilder();
            case self::PUBLIC_GAME : return new PublicGameBuilder();
            case self::AI_GAME : return new AIGameBuilder();
            default: throw new \Exception('Unknow game type');
        }
    }

}
