<?php

namespace AppBundle\Game;

use AppBundle\Storage\ObjectStorage;
use Appbundle\ConfigContainer;
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
     * @var ConfigContainer
     */
    private $config;

    public function __construct(ConfigContainer $config) {
        $this->config = $config;
        $this->gamesRepository = new ObjectStorage();
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
