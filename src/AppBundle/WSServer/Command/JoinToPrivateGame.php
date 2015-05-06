<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GameSystem;
use AppBundle\WSServer\Message;

/**
 * @author dkociuba
 */
class JoinToPrivateGame implements WSCommandInterface {

    /**
     *
     * @var GameSystem
     */
    private $gameSystem;

    public function __construct(GameSystem $gameSystem) {
        $this->gameSystem = $gameSystem;
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

        $game = $this->gameSystem->getGamesRepository()->findFirstBy(array('hashId' => $gameHash));
        $firstPlayer = $this->getFirstPlayer($game);
        $secondPlayer = $this->gameSystem->createPlayer($playerName);
        $secondPlayer->setConnection($message->getConnection());
        $game->addPlayer($secondPlayer);

        $firstPlayer->getConnection()->send(json_encode(array(
            'command' => 'SecondPlayerJoinToPrivateGame'
        )));
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

//put your code here
}
