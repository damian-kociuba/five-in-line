<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\Game\GameBuilder;
use AppBundle\Game\PlayerBuilder;
use AppBundle\WSServer\Response\StartGame;
use AppBundle\WSServer\Message;

/**
 * @author dkociuba
 */
class CreateGameVsAI implements WSCommandInterface {

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
        echo 'Create Game vs AI';
        $playerBuilder = new PlayerBuilder();
        
        $playerBuilder->setPlayerName('Player');
        $player = $playerBuilder->createPlayer(PlayerBuilder::AI_PLAYER);
        
        $player->setConnection($message->getConnection());
        $player->setColor('white');
        
        $playerBuilder->setPlayerName('Computer');
        $opponent = $playerBuilder->createPlayer(PlayerBuilder::AI_PLAYER);
        $opponent->setColor('black');
        $opponent->setOpponent($player);

        $this->gameBuilder->setCreator($player);
        $game = $this->gameBuilder->createGame(GameBuilder::AI_GAME);
        
        $opponent->setBoard($game->getBoard());
        $game->addPlayer($opponent);


        $game->setFirstMovePlayer($player);
        $this->gamesRepository->attach($game);
        $response = new StartGame();
        $response->setIsPlayerTurn(true);
        $response->setPlayer($player);
        $response->setOpponent($opponent);
        $player->getConnection()->send($response);
    }

    public function getCommandName() {
        return 'CreateGameVsAI';
    }

    /**
     * @param array $parameters
     * @throws \Exception
     */
    public function validateParameters(array $parameters) {
        
    }

    public function getType() {
        return WSCommandInterface::ON_MESSAGE_TYPE;
    }

}
