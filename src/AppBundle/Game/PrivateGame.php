<?php

namespace AppBundle\Game;

/**
 * Description of PrivateGame
 *
 * @author dkociuba
 */
class PrivateGame extends Game {

    private $hashId;

    public function __construct($boardWidth, $boardHeight) {
        parent::__construct($boardWidth, $boardHeight);
    }
    public function setHashId($hash) {

        $this->hashId = $hash;
    }
    public function getHashId() {
        return $this->hashId;
    }

}
