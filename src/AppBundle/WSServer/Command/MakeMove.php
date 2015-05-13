<?php

namespace AppBundle\WSServer\Command;

use AppBundle\WSServer\Message;
use AppBundle\Game\GameSystem;
use AppBundle\Storage\ObjectStorage;
use AppBundle\Game\Game;
use Ratchet\ConnectionInterface;

/**
 * @author dkociuba
 * 
 * Command input:
 * game_id 
 */
class MakeMove implements WSCommandInterface {

    /**
     *
     * @var GameSystem
     */
    private $gameSystem;

    public function __construct(GameSystem $gameSystem) {
        $this->gameSystem = $gameSystem;
    }

    public function getCommandName() {
        return 'MakeMove';
    }

    public function run(Message $message) {
        $game = $this->getGameByConnection($this->gameSystem->getGamesRepository(), $message->getConnection());
        if(!$game) {
            throw new \Exception('This connection dont have active game');
        }
        $players = $game->getPlayers();
        if ($players[0]->getConnection() === $message->getConnection()) {
            $player = $players[0];
            $opponent = $players[1];
        } else {
            $player = $players[1];
            $opponent = $players[0];
        }
        
        if($game->getNextMovingPlayer() !== $player) {
            throw new \Exception('It\'s not your turn');
        }
        $parameters = $message->getParameters();
        $game->getBoard()->markField($parameters['x'], $parameters['y'], $player->getColor());
        $game->changePlayerTurn();
        
        $player->getConnection()->send(json_encode(array(
            'command' => 'MoveMade',
            'parameters' => array(
                'color' => $player->getColor(),
                'x' => $parameters['x'], 
                'y' => $parameters['y'],
                'isPlayerTurn' => false,
            )
        )));
        $opponent->getConnection()->send(json_encode(array(
            'command' => 'MoveMade',
            'parameters' => array(
                'color' => $player->getColor(),
                'x' => $parameters['x'], 
                'y' => $parameters['y'],
                'isPlayerTurn' => true,
            )
        )));
    }

    /**
     * 
     * @param ObjectStorage $gamesRepository
     * @param ConnectionInterface $connection
     * @return Game
     */
    private function getGameByConnection(ObjectStorage $gamesRepository, ConnectionInterface $connection) {
        foreach ($gamesRepository as $game) {
            $players = $game->getPlayers();
            foreach ($players as $onePlayer) {
                if ($onePlayer->getConnection() == $connection) {
                    return $game;
                }
            }
            return null;
        }
    }

    public function validateParameters(array $parameters) {
        if (empty($parameters['x']) || empty($parameters['y'])) {
            throw new \Exception('Parameter x and y is required');
        }
    }

}
