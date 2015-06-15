<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GameSystem;
use AppBundle\Game\GameBuilder;
use AppBundle\WSServer\Response\PrivateGameCreated;
use AppBundle\WSServer\Message;

/**
 * @author dkociuba
 */
class CreatePrivateGame implements WSCommandInterface {

    /**
     * @var GameSystem
     */
    private $gameSystem;
    /**
     * @var GameBuilder
     */
    private $gameBuilder;

    public function __construct(GameSystem $gameSystem, GameBuilder $gameBuilder) {
        $this->gameSystem = $gameSystem;
        $this->gameBuilder = $gameBuilder;
    }

    public function run(Message $message) {
        $parameters = $message->getParameters();
        echo 'Create Private Game';
        $player = $this->gameSystem->createPlayer($parameters['playerName']);
        $player->setConnection($message->getConnection());
        
        $this->gameBuilder->setCreator($player);
        $game = $this->gameBuilder->createGame(GameBuilder::PRIVATE_GAME);
        
        $this->gameSystem->getGamesRepository()->attach($game);

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

    public function getType() {
        return WSCommandInterface::ON_MESSAGE_TYPE;
    }

}
