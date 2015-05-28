<?php

namespace AppBundle\WSServer;

use Ratchet\ConnectionInterface;

class ArtificialConnection implements ConnectionInterface {

    private $sendedData;

    public function close() {
        
    }

    public function send($data) {
        $this->sendedData = $data;
    }

    public function getSendedData() {
        return $this->sendedData;
    }

}
