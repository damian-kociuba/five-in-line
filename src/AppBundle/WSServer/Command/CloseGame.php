<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GameSystem;
use AppBundle\WSServer\Response\PrivateGameCreated;
use AppBundle\Storage\ObjectStorage;
use AppBundle\WSServer\Message;

/**
 * @author dkociuba
 */
class CloseGame implements WSCommandInterface {

    /**
     *
     * @var GameSystem
     */
    private $gameSystem;

    public function __construct(GameSystem $gameSystem) {
        $this->gameSystem = $gameSystem;
    }

    public function run(Message $message) {
        echo 'close game';
        $connection = $message->getConnection();
        $gamesRepository = $this->gameSystem->getGamesRepository();

        $gameToClose = $this->getGameByConnection($gamesRepository, $connection);
        if ($gameToClose === null) {
            echo ' - nothing to close';
            return;
        }
        
        $gamesRepository->detach($gameToClose);
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
