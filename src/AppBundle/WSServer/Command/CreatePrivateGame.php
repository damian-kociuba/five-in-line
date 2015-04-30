<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GameSystem;
use AppBundle\WSServer\Response\PrivateGameCreated;
/**
 * @author dkociuba
 */
class CreatePrivateGame implements WSCommandInterface {

    public function run($message) {
        $parameters = $message['parameters'];
        echo 'Create Private Game';
        $gameSystem = GameSystem::getInstance();
        $player = $gameSystem->createPlayer($parameters['playerName']);
        $game = $gameSystem->createPrivateGame($player);
        $gameSystem->getGamesRepository()->attach($game);
        
        $response = new PrivateGameCreated();
        $response->setGameHashId($game->getHashId());
        return $response;
        
    }

    public function getCommandName() {
        return 'CreatePrivateGame';
    }

    /**
     * @param array $parameters
     * @throws \Exception
     */
    public function validateParameters(array $parameters) {
        if (!isset($parameters['playerName'])) {
            throw new \Exception('CreatePrivateGame command needs playerName parameter');
        }
    }

}
