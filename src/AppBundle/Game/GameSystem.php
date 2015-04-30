<?php

namespace AppBundle\Game;

/**
 * Description of GameSystem
 *
 * @author dkociuba
 */
class GameSystem {

    private static $instance = null;

    /**
     * @return GameSystem
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @var \SplObjectStorage
     */
    private $gamesRepository;

    public function __construct() {
        $this->gamesRepository = new \SplObjectStorage();
    }

    public function getGamesRepository() {
        return $this->gamesRepository;
    }

    /**
     * 
     * @param Player $playerCreator
     * @return PrivateGame
     */
    public function createPrivateGame(Player $playerCreator) {
        $game = new PrivateGame();
        $game->addPlayer($playerCreator);
        $game->setHashId(uniqid());
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

}
