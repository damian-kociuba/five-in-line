<?php

namespace AppBundle\Game;
use AppBundle\Storage\ObjectStorage;
use AppBundle\Game\GameBuilder;
/**
 * Description of PublicGamesManager
 *
 * @author dkociuba
 */
class PublicGamesManager {
    
    /**
     *
     * @var ObjectStorage
     */
    private $gameRepository;
    public function __construct(ObjectStorage $gameRepository) {
        $this->gameRepository = $gameRepository;
    }
    
    public function getJoinableGame() {
        
    }
    
    /**
     * @param Player $playerCreator
     * @return PrivateGame
     */
    public function createPublicGame(Player $playerCreator) {
        $game = new PublicGame($this->configValues['boardWidth'], $this->configValues['boardHeight']);

        $game->addPlayer($playerCreator);
        return $game;
    }
}
