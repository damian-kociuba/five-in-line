<?php

namespace AppBundle\Game;

use AppBundle\ConfigContainer;
/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-06-15 at 11:28:51.
 */
class PublicGamesManagerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var PublicGamesManager
     */
    protected $object;
    
    private $publicJoinableGame;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $gameRepository = new \AppBundle\Storage\ObjectStorage();
        $config = new ConfigContainer(array(
            'boardWidth' => 10,
            'boardHeight' => 10)
        );
        $gameBuilder = new GameBuilder($config);
        $creator = new Player();
        $opponent = new Player();
        $gameBuilder->setCreator($creator);
        
        $privateGame = $gameBuilder->createGame(GameBuilder::PRIVATE_GAME);
        $publicFullGame = $gameBuilder->createGame(GameBuilder::PUBLIC_GAME);
        $publicFullGame->addPlayer($opponent);
        $this->publicJoinableGame = $gameBuilder->createGame(GameBuilder::PUBLIC_GAME);

        $gameRepository->attach($privateGame);
        $gameRepository->attach($publicFullGame);
        $gameRepository->attach($this->publicJoinableGame);
        
        $this->object = new PublicGamesManager($gameRepository);
    }

    /**
     * @covers AppBundle\Game\PublicGamesManager::getJoinableGame
     */
    public function testGetJoinableGame() {
        $result = $this->object->getFirstJoinableGame();
        $this->assertSame($this->publicJoinableGame, $result);
    }

}
