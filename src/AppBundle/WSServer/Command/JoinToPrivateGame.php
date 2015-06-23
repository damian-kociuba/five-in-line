<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\WSServer\Message;
use AppBundle\WSServer\Response\StartGame;
use AppBundle\Game\PlayerBuilderSupervisor;
use AppBundle\Game\PlayerBuilder\HumanPlayerBuilder;

/**
 * @author dkociuba
 */
class JoinToPrivateGame implements WSCommandInterface {

    /**
     *
     * @var GamesRepository
     */
    private $gamesRepository;

    public function __construct(GamesRepository $gamesRepository) {
        $this->gamesRepository = $gamesRepository;
    }

    public function getCommandName() {
        return 'JoinToPrivateGame';
    }

    public function validateParameters(array $parameters) {
        if (empty($parameters['gameHash'])) {
            throw new \Exception('Parameter "gameHash" is required by JoinToPrivateGame command');
        }
        if (empty($parameters['playerName'])) {
            throw new \Exception('Parameter "playerName" is required by JoinToPrivateGame command');
        }
    }

    public function run(Message $message) {
        echo 'Join to private game';
        $parameters = $message->getParameters();
        $gameHash = $parameters['gameHash'];
        $playerName = $parameters['playerName'];

        $game = $this->gamesRepository->findFirstBy(array('hashId' => $gameHash));
        $firstPlayer = $this->getFirstPlayer($game);

        $playerBuilder = new PlayerBuilderSupervisor();

        $playerBuilder->setPlayerName($playerName);
        $secondPlayer = $playerBuilder->createPlayer(new HumanPlayerBuilder());

        $secondPlayer->setConnection($message->getConnection());
        $game->addPlayer($secondPlayer);

        $firstPlayerColor = (rand(0, 1) == 1 ? 'black' : 'white'); //randomly black or white
        $secondPlayerColor = $firstPlayerColor == 'black' ? 'white' : 'black'; //opposed color
        $isFirstPlayerTurn = rand(0, 1) == 1; //randomly true or false

        $firstPlayer->setColor($firstPlayerColor);
        $secondPlayer->setColor($secondPlayerColor);
        if ($isFirstPlayerTurn) {
            $game->setFirstMovePlayer($firstPlayer);
        } else {
            $game->setFirstMovePlayer($secondPlayer);
        }

        $responseToFirstPlayer = new StartGame();
        $responseToFirstPlayer->setIsPlayerTurn($isFirstPlayerTurn);
        $responseToFirstPlayer->setPlayer($firstPlayer);
        $responseToFirstPlayer->setOpponent($secondPlayer);
        $firstPlayer->getConnection()->send($responseToFirstPlayer);

        $responseToSecondPlayer = new StartGame();
        $responseToSecondPlayer->setIsPlayerTurn(!$isFirstPlayerTurn);
        $responseToSecondPlayer->setPlayer($secondPlayer);
        $responseToSecondPlayer->setOpponent($firstPlayer);
        $secondPlayer->getConnection()->send($responseToSecondPlayer);
    }

    private function getFirstPlayer(\AppBundle\Game\Game $game) {
        $players = $game->getPlayers();
        if (count($players) > 1) {
            throw new \Exception('Game is full, player cannot join');
        }
        if (count($players) < 1) {
            throw new \Exception('Game does not have any players, player cannot join');
        }
        return $players[0];
    }

    public function getType() {
        return WSCommandInterface::ON_MESSAGE_TYPE;
    }

}
