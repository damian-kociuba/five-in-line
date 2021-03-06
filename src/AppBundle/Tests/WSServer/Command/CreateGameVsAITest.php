<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\Tests\WSServer\ConnectionMock;
use AppBundle\ConfigContainer;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-05-22 at 11:47:44.
 */
class CreateGameVsAITest extends \PHPUnit_Framework_TestCase {

    /**
     * @var CreateGameVsAI
     */
    protected $object;

    /**
     * @var GamesRepository
     */
    private $gamesRepository;

    /**
     * @var ConnectionMock
     */
    private $connection;
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $config = new ConfigContainer(array(
            'boardWidth' => 20,
            'boardHeight' => 20
        ));
        $gameBuilder = new \AppBundle\Game\GameBuilderSupervisor($config);
        $this->gamesRepository = new GamesRepository();
        $this->object = new CreateGameVsAI($this->gamesRepository, $gameBuilder);
        $this->connection = new ConnectionMock();
    }

    /**
     * @covers AppBundle\WSServer\Command\CreateGameVsAI::run
     */
    public function testRun_IsCreatedNewGame() {
        $message = new \AppBundle\WSServer\Message();
        $message->setConnection($this->connection);
        $this->object->run($message);
        $this->assertEquals(1, $this->gamesRepository->count());
    }

    /**
     * @covers AppBundle\WSServer\Command\CreateGameVsAI::run
     */
    public function testRun_IsCreatedNewGameWhichIsAIGame() {
        $message = new \AppBundle\WSServer\Message();
        $message->setConnection($this->connection);

        $this->object->run($message);
        $this->gamesRepository->rewind();
        $this->assertInstanceOf('\AppBundle\Game\AI\AIGame', $this->gamesRepository->current());
    }
    /**
     * @covers AppBundle\WSServer\Command\CreateGameVsAI::run
     */
    public function testRun_IsCorrectlyResponseSended() {
        $message = new \AppBundle\WSServer\Message();
        $message->setConnection($this->connection);

        $this->object->run($message);
        $response = json_decode($this->connection->getSendedData(), true);
        $this->assertEquals('StartGame', $response['command']);
        $this->assertTrue(in_array($response['parameters']['playerColor'], array('black', 'white')), 'Player\'s color isnt black neither white. Given: '.$response['parameters']['playerColor']);
        $this->assertInternalType('boolean', $response['parameters']['isPlayerTurn']);
        $this->assertEquals('Computer', $response['parameters']['opponentName']);
    }

    /**
     * @covers AppBundle\WSServer\Command\CreateGameVsAI::getCommandName
     */
    public function testGetCommandName() {
        $this->assertEquals('CreateGameVsAI', $this->object->getCommandName());
    }

    /**
     * @covers AppBundle\WSServer\Command\CreateGameVsAI::getType
     */
    public function testGetType() {
        $this->assertEquals(WSCommandInterface::ON_MESSAGE_TYPE, $this->object->getType());
    }

}
