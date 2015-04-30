<?php

namespace AppBundle\Game;

/**
 * Description of Game
 *
 * @author dkociuba
 */
class Game {
    /**
     * @var Player[]
     */
    private $players = array();
    
    /**
     *
     * @var Player;
     */
    private $nextMovingPlayer;
    
    public function addPlayer(Player $player) {
        $this->players[] = $player;
    }
    
    
}
