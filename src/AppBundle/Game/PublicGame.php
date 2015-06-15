<?php

namespace AppBundle\Game;

/**
 * Description of PrivateGame
 *
 * @author dkociuba
 */
class PublicGame extends Game {

    public function isPossibleToJoin() {
        return $this->getPlayers()===1;
    }

}
