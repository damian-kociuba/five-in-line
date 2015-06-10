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

    public function __construct(EntityManagerInterface $em, array $registeredCommandClasses) {
        $this->connections = new \SplObjectStorage();
        $this->em = $em;
        $this->registeredCommands = $registeredCommandClasses;
    }

    public function onOpen(ConnectionInterface $conn) {
        echo 'Connectio open';
        $this->connections->attach($conn);
    }

    public function onClose(ConnectionInterface $connection) {

        $message = new Message();
        $message->setConnection($connection);
        foreach ($this->registeredCommands as $command) {
            if ($command->getType() === WSCommandInterface::ON_CLOSE_TYPE) {
                $command->run($message);
            }
        }

        $this->connections->detach($connection);
        echo 'Connection close';
    }

    /**
     * @param ConnectionInterface $connection
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $connection, $msg) {
//        $connection->send('{"command":"someString","data":"data"}');
//        sleep(10);
//        return;
//        echo "Message: \n" . $msg . "\n";
        $message = new Message();
        $message->setConnection($connection);
        $message->readFromJSON($msg);
        $command = $this->getCommandByMessage($message);
        try {
            $command->validateParameters($message->getParameters());
            $result = $command->run($message);
            if ($result !== null) {
                $this->sendResponse($connection, $result);
            }
        } catch (\Exception $e) {

            echo 'Error in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getMessage() . "\n";
            echo $e->getTraceAsString();
            $connection->send(json_encode(array(
                'command' => 'Error',
                'parameters' => array(
                    'message' => $e->getMessage()
                )
            )));
        }
    }

    /**
     * @param array $message
     * @return WSCommandInterface
     * @throws \Exception
     */
    private function getCommandByMessage(Message $message) {
        foreach ($this->registeredCommands as $command) {
            if ($command->getType() !== WSCommandInterface::ON_MESSAGE_TYPE) {
                continue;
            }
            if ($command->getCommandName() === $message->getCommandName()) {
                return $command;
            }
        }
        throw new \Exception('Command "' . $message->getCommandName() . '" not found');
    }

    private function sendResponse(ConnectionInterface $connection, $response) {
        if (!($response instanceof Response\ResponseInterface)) {
            throw new \Exception('Response which is returned by command should implements ResponseInterface');
        }
        
        $connection->send($response->getAsString());
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo 'Error';
        throw $e;
    }

}
