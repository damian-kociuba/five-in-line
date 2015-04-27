<?php

namespace AppBundle\WSServer;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\WSServer\Command\WSCommandInterface;

/**
 * This class is responsible for detect command from WebSocket message and run 
 * appropriate class of CommandInterface
 *
 * @author dkociuba
 */
class CommandManager implements MessageComponentInterface {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var WSCommandInterface[] 
     */
    private $registeredCommands = array();

    public function __construct(EntityManagerInterface $em, array $registeredCommandClassNames) {
        $this->em = $em;
        foreach ($registeredCommandClassNames as $className) {
            $this->registeredCommands[] = new $className;
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        echo 'Connection open';
    }

    public function onClose(ConnectionInterface $conn) {
        echo 'Connection close';
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Message: \n" . $msg;

        $decodedMessage = json_decode($msg, true);
        $this->validateMessage($decodedMessage);
        $command = $this->getCommandByMessage($decodedMessage);
        $command->run($decodedMessage);
    }

    private function validateMessage($message) {
        if (!is_array($message)) {
            throw new \Exception('Message should be array given ' . gettype($message));
        }
        if (empty($message['command'])) {
            throw new \Exception('Message array should have "command" field');
        }
        
    }

    /**
     * @param array $message
     * @return WSCommandInterface
     * @throws \Exception
     */
    private function getCommandByMessage(array $message) {
        foreach ($this->registeredCommands as $command) {
            if ($command->getCommandName() === $message['command']) {
                return $command;
            }
        }
        throw new \Exception('Command "' . $message['type'] . '" not found');
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo 'Error';
    }

}
