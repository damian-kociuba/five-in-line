<?php

namespace AppBundle\WSServer;

use Ratchet\ConnectionInterface;

/**
 * Description of Message
 *
 * @author dkociuba
 */
class Message {

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var string
     */
    private $commandName;

    /**
     * @var array
     */
    private $parameters;

    public function setConnection(ConnectionInterface $connection) {
        $this->connection = $connection;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection() {
        return $this->connection;
    }

    public function getCommandName() {
        return $this->commandName;
    }

    public function getParameters() {
        return $this->parameters;
    }

    public function readFromJSON($jsonMessage) {
        $decodedMessage = json_decode($jsonMessage, true);

        $this->readFromArrayMessage($decodedMessage);
    }

    public function readFromArrayMessage($message) {
        $this->validateDecodedMessage($message);
        $this->commandName = $message['command'];
        $this->parameters = $message['parameters'];
    }

    /**
     * @param type $message
     * @throws \Exception
     */
    private function validateDecodedMessage($message) {
        if (!is_array($message)) {
            throw new \Exception('Message should be array, given ' . gettype($message));
        }
        if (empty($message['command'])) {
            throw new \Exception('Message array should have "command" field');
        }
    }

}
