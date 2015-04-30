<?php

namespace AppBundle\Game;

/**
 * Description of PrivateGame
 *
 * @author dkociuba
 */
class PrivateGame extends Game {

    private $hashId;

    public function setHashId($hash) {

        $this->hashId = $hash;
    }
    public function getHashId() {
        return $this->hashId;
    }

}
