<?php

namespace AppBundle\WSServer\Response;

/**
 * @author dkociuba
 */
class PrivateGameCreated implements ResponseInterface {

    private $gameHashId;

    public function setGameHashId($hash) {
        $this->gameHashId = $hash;
    }
    
    public function getGameHashId() {
        return $this->gameHashId;
    }

    public function getData() {
        return array('gameHashId' => $this->getGameHashId());
    }

    public function getName() {
        return 'PrivateGameCreated';
    }

}
