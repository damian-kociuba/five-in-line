<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\Game\GameBuilder;
use AppBundle\Game\PlayerBuilder;
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
     * @var GameBuilder
     */
    private $gameBuilder;

    public function __construct(GamesRepository $gamesRepository, GameBuilder $gameBuilder) {
        $this->gamesRepository = $gamesRepository;
        $this->gameBuilder = $gameBuilder;
    }

    public function run(Message $message) {
        $parameters = $message->getParameters();
        echo 'Create Private Game';
         $playerBuilder = new PlayerBuilder();
        
        $playerBuilder->setPlayerName($parameters['playerName']);
        $player = $playerBuilder->createPlayer(PlayerBuilder::HUMAN_PLAYER);
        $player->setConnection($message->getConnection());
        
        $this->gameBuilder->setCreator($player);
        $game = $this->gameBuilder->createGame(GameBuilder::PRIVATE_GAME);
        
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
