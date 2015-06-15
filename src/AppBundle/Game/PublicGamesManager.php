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
    
    public function getFirstJoinableGame() {
        foreach($this->gameRepository as $game) {
            if(! $game instanceof PublicGame) {
                continue;
            }
            
            if($game->isPossibleToJoin()) {
                return $game;
            }
        }
        return null;
    }
    
}
