<?php

namespace AppBundle\Game;

use Ratchet\ConnectionInterface;

/**
 * @author dkociuba
 */
class Player {

    private $name;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setConnection(ConnectionInterface $connection) {
        $this->connection = $connection;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection() {
        return $this->connection;
    }

}
