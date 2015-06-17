<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\WSServer\Response\PrivateGameCreated;
use AppBundle\Storage\ObjectStorage;
use AppBundle\WSServer\Message;

/**
 * @author dkociuba
 */
class CloseGame implements WSCommandInterface {

    /**
     *
     * @var GamesRepository
     */
    private $gamesRepository;

    public function __construct(GamesRepository $gamesRepository) {
        $this->gamesRepository = $gamesRepository;
    }

    public function run(Message $message) {
        echo 'close game';
        $connection = $message->getConnection();

        $gameToClose = $this->getGameByConnection($this->gamesRepository, $connection);
        if ($gameToClose === null) {
            echo ' - nothing to close';
            return;
        }
        
        $this->gamesRepository->detach($gameToClose);
        $players = $gameToClose->getPlayers();
        foreach ($players as $player) {
            $currentConnection = $player->getConnection();
            if ($currentConnection !== $connection) {
                
                $currentConnection->send(json_encode(array(
                    'command' => 'CloseGame',
                    'parameters' => array()
                )));
            }
        }
    }

    /**
     * 
     * @param ObjectStorage $gamesRepository
     * @param ConnectionInterface $connection
     * @return Game
     */
    private function getGameByConnection(ObjectStorage $gamesRepository, \Ratchet\ConnectionInterface $connection) {
        foreach ($gamesRepository as $game) {
            $players = $game->getPlayers();
            foreach ($players as $onePlayer) {
                if ($onePlayer->getConnection() === $connection) {
                    return $game;
                }
            }
        }
        return null;
    }

    public function getCommandName() {
        return 'CloseGame';
    }

    /**
     * @param array $parameters
     * @throws \Exception
     */
    public function validateParameters(array $parameters) {
        
    }

    public function getType() {
        return WSCommandInterface::ON_CLOSE_TYPE;
    }

}
