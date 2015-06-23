<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\Game\GameBuilderSupervisor;
use AppBundle\Game\GameBuilder\PrivateGameBuilder;
use AppBundle\Game\PlayerBuilderSupervisor;
use AppBundle\Game\PlayerBuilder\HumanPlayerBuilder;
use AppBundle\WSServer\Response\PrivateGameCreated;
use AppBundle\WSServer\Message;

/**
 * @author dkociuba
 */
class CreatePrivateGame implements WSCommandInterface {

    /**
     * @var GamesRepository
     */
    private $gamesRepository;
    /**
     * @var GameBuilderSupervisor
     */
    private $gameBuilder;

    public function __construct(GamesRepository $gamesRepository, GameBuilderSupervisor $gameBuilder) {
        $this->gamesRepository = $gamesRepository;
        $this->gameBuilder = $gameBuilder;
    }

    public function run(Message $message) {
        $parameters = $message->getParameters();
        echo 'Create Private Game';
         $playerBuilder = new PlayerBuilderSupervisor();
        
        $playerBuilder->setPlayerName($parameters['playerName']);
        $player = $playerBuilder->createPlayer(new HumanPlayerBuilder());
        $player->setConnection($message->getConnection());
        
        $this->gameBuilder->setCreator($player);
        $game = $this->gameBuilder->createGame(new PrivateGameBuilder());
        
        $this->gamesRepository->attach($game);

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
