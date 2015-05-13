<?php

namespace AppBundle\Game;

/**
 * Description of Judge
 *
 * @author dkociuba
 */
class Judge {
    const FIRST_PLAYER_WIN = 1;
    const SECOND_PLAYER_WIN = 2;
    const DRAW = 3;
    const CONTINUE_PLAYING = 4;
    
    public function check(Board $board, Player $firstPlayer, Player $secondPlayer) {
        
    }
}
