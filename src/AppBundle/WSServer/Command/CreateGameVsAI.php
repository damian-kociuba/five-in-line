<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\Game\GameBuilderSupervisor;
use AppBundle\Game\GameBuilder\AIGameBuilder;
use AppBundle\Game\PlayerBuilderSupervisor;
use AppBundle\Game\PlayerBuilder\AIPlayerBuilder;
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
     * @var GameBuilderSupervisor
     */
    private $gameBuilder;

    public function __construct(GamesRepository $gamesRepository, GameBuilderSupervisor $gameBuilder) {
        $this->gamesRepository = $gamesRepository;
        $this->gameBuilder = $gameBuilder;
    }

    public function run(Message $message) {
        echo 'Create Game vs AI';
        $playerBuilder = new PlayerBuilderSupervisor();
        
        $playerBuilder->setPlayerName('Player');
        $player = $playerBuilder->createPlayer(new AIPlayerBuilder());
        
        $player->setConnection($message->getConnection());
        $player->setColor('white');
        
        $playerBuilder->setPlayerName('Computer');
        $opponent = $playerBuilder->createPlayer(new AIPlayerBuilder());
        $opponent->setColor('black');
        $opponent->setOpponent($player);

        $this->gameBuilder->setCreator($player);
        $game = $this->gameBuilder->createGame(new AIGameBuilder());
        
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
