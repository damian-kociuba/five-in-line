<?php

namespace AppBundle\Game\AI;
use AppBundle\Game\Game;
/**
 * @author dkociuba
 */
class AIGame extends Game {


    public function __construct($boardWidth, $boardHeight) {
        parent::__construct($boardWidth, $boardHeight);
    }

}
