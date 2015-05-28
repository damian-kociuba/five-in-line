<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GameSystem;
use AppBundle\WSServer\Response\StartGame;
use AppBundle\WSServer\Message;

/**
 * @author dkociuba
 */
class CreateGameVsAI implements WSCommandInterface {

    /**
     *
     * @var GameSystem
     */
    private $gameSystem;

    public function __construct(GameSystem $gameSystem) {
        $this->gameSystem = $gameSystem;
    }

    public function run(Message $message) {
        echo 'Create Game vs AI';
        $player = $this->gameSystem->createPlayer('Player');
        $player->setConnection($message->getConnection());
        $player->setColor('white');
        $opponent = $this->gameSystem->createAIPlayer('Computer');
        $opponent->setColor('black');
        $opponent->setOpponent($player);
        
        $game = $this->gameSystem->createAIGame($player);
        $opponent->setBoard($game->getBoard());
        $game->addPlayer($opponent);
        
        var_dump(get_class($opponent));
        
        $game->setFirstMovePlayer($player);
        $this->gameSystem->getGamesRepository()->attach($game);
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
