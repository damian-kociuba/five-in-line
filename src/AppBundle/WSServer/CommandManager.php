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
    
    /**
     * @var \SplObjectStorage
     */
    private $connections;
    

    public function __construct(EntityManagerInterface $em, array $registeredCommandClassNames) {
        $this->connections = new \SplObjectStorage();
        $this->em = $em;
        foreach ($registeredCommandClassNames as $className) {
            $this->registeredCommands[] = new $className;
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->connections->attach($conn);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->connections->detach($conn);
        echo 'Connection close';
    }

    /**
     * @param ConnectionInterface $connection
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $connection, $msg) {
        echo "Message: \n" . $msg;
        
        $decodedMessage = json_decode($msg, true);
        $this->validateMessage($decodedMessage);
        $command = $this->getCommandByMessage($decodedMessage);
        $command->validateParameters($decodedMessage['parameters']);
        $result = $command->run($decodedMessage);
        if ($result !== null) {
            $this->sendResponse($connection, $result);
        }
    }

    /**
     * @param type $message
     * @throws \Exception
     */
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

    private function sendResponse(ConnectionInterface $connection, $response) {
        if (!($response instanceof Response\ResponseInterface)) {
            throw new \Exception('Response which is returned by command should implements ResponseInterface');
        }
        
        $dataToSend = array('command' => $response->getName(), 'data'=>$response->getData());
        $connection->send(json_encode($dataToSend));
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo 'Error';
        throw $e;
    }

}
