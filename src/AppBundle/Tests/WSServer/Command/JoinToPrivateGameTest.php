<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GameSystem;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-05-06 at 14:53:42.
 */
class JoinToPrivateGameTest extends \PHPUnit_Framework_TestCase {

    const TEST_PLAYER_NAME = 'joiningPlayer';
    const TEST_GAME_HASH = '123456789';

    /**
     * @var JoinToPrivateGame
     */
    protected $object;

    /**
     * @var GameSystem
     */
    private $gameSystem;

    protected function setUp() {
        $this->gameSystem = new GameSystem(array('boardWidth'=>20,'boardHeight'=>20));
        $player = $this->gameSystem->createPlayer('firstPlayer');
        $connection = new \AppBundle\Tests\WSServer\ConnectionMock();
        $player->setConnection($connection);
        $game = $this->gameSystem->createPrivateGame($player);
        $game->setHashId(self::TEST_GAME_HASH);
        $this->gameSystem->getGamesRepository()->attach($game);

        $this->object = new JoinToPrivateGame($this->gameSystem);
    }

    /**
     * @covers AppBundle\WSServer\Command\JoinToPrivateGame::validateParameters
     * @expectedException Exception
     */
    public function testValidateParametersWhenGameHashNonExists() {
        $parameters = array(
            'playerName' => 'name',
        );
        $this->object->validateParameters($parameters);
    }

    /**
     * @covers AppBundle\WSServer\Command\JoinToPrivateGame::validateParameters
     * @expectedException Exception
     */
    public function testValidateParametersWhenPlayerNameNonExists() {
        $parameters = array(
            'gameHash' => 'name',
        );
        $this->object->validateParameters($parameters);
    }

    /**
     * @covers AppBundle\WSServer\Command\JoinToPrivateGame::run
     */
    public function testRunAfterJoinIsTwoPlayers() {

        $message = $this->prepareMessage();
        $this->object->run($message);
        $game = $this->gameSystem->getGamesRepository()->current(); //there is only one game
        $this->assertEquals(2, count($game->getPlayers()));
    }

    public function testRunAfterJoinAddedPlayerIsCorrect() {
        $message = $this->prepareMessage();
        $this->object->run($message);
        $game = $this->gameSystem->getGamesRepository()->current(); //there is only one game
        $addedPlayer = $game->getPlayers()[1]; //second player
        $this->assertEquals(self::TEST_PLAYER_NAME, $addedPlayer->getName());
    }

    public function testRunAfterJoinMessageIsSendedToFirstPlayer() {
        $message = $this->prepareMessage();
        $this->object->run($message);
        $game = $this->gameSystem->getGamesRepository()->current(); //there is only one game
        $firstPlayer = $game->getPlayers()[0];
        $connectionMock = $firstPlayer->getConnection();
        $sendedData = json_decode($connectionMock->getSendedData(), true);
        
        $this->assertEquals('StartGame', $sendedData['command']);
    }

    private function prepareMessage() {
        $message = new \AppBundle\WSServer\Message();
        $message->readFromArrayMessage(array(
            'command' => 'JoinToPrivateGame',
            'parameters' => array(
                'gameHash' => self::TEST_GAME_HASH,
                'playerName' => self::TEST_PLAYER_NAME,
            )
        ));
        $connection = new \AppBundle\Tests\WSServer\ConnectionMock();
        $message->setConnection($connection);
        return $message;
    }

}
