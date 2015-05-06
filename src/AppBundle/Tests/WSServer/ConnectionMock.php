<?php

namespace AppBundle\Tests\WSServer;

use Ratchet\ConnectionInterface;

class ConnectionMock implements ConnectionInterface {

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
