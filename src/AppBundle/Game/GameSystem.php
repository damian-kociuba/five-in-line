<?php

namespace AppBundle\Game;

use AppBundle\Storage\ObjectStorage;

/**
 * Description of GameSystem
 *
 * @author dkociuba
 */
class GameSystem {

    /**
     * @var ObjectSorage
     */
    private $gamesRepository;

    /**
     * @var array
     */
    private $configValues;

    public function __construct($configValues) {
        $this->configValues = $configValues;
        $this->gamesRepository = new ObjectStorage();
        echo 'Game system construct';
    }

    /**
     * 
     * @return ObjectStorage
     */
    public function getGamesRepository() {
        return $this->gamesRepository;
    }

### Refactoring: Move  functions below to other place

    /**
     * @param Player $playerCreator
     * @return PrivateGame
     */
    public function createPrivateGame(Player $playerCreator) {
        $game = new PrivateGame($this->configValues['boardWidth'], $this->configValues['boardHeight']);

        $game->addPlayer($playerCreator);
        $game->setHashId(uniqid());
        return $game;
    }
    /**
     * @param Player $playerCreator
     * @return AI\AIGame
     */
    public function createAIGame(Player $playerCreator) {
        $game = new AI\AIGame($this->configValues['boardWidth'], $this->configValues['boardHeight']);

        $game->addPlayer($playerCreator);
        return $game;
    }

    /**
     * 
     * @param string $name
     * @return Player
     */
    public function createPlayer($name) {
        $player = new Player();
        $player->setName($name);
        return $player;
    }

    /**
     * 
     * @param string $name
     * @return Player
     */
    public function createAIPlayer($name) {
        $player = new AI\AIPlayer();
        $player->setName($name);
        $player->setConnection(new \AppBundle\WSServer\ArtificialConnection());
        return $player;
    }

}
