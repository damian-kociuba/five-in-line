<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\WSServer\Response\PrivateGameCreated;
use AppBundle\WSServer\Message;
use AppBundle\Game\PlayerBuilderSupervisor;
use AppBundle\Game\PlayerBuilder\HumanPlayerBuilder;
use AppBundle\Game\PublicGamesManager;
use AppBundle\Game\PublicGame;
use AppBundle\Game\Player;
use AppBundle\Game\GameInitializer;
use AppBundle\Game\GameBuilderSupervisor;
use AppBundle\Game\GameBuilder\PublicGameBuilder;

/**
 * @author dkociuba
 */
class CreateOrJoinPublicGame implements WSCommandInterface {

    /**
     *
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

        
        $publicGamesManager = new PublicGamesManager($this->gamesRepository);
        $joinableGame = $publicGamesManager->getFirstJoinableGame();
        
        $newPlayer = $this->prepareNewPlayer($message);


        if ($joinableGame === null) {
            echo 'Create Public Game';
            $this->addNewPublicGame($message);
        } else {
            echo 'Join to Public Game';
            $playerCreator = $joinableGame->getPlayers()[0];
            
            $this->joinPlayerToGame($joinableGame, $newPlayer);
            $gameInitializer = new GameInitializer();
            $gameInitializer->initializeByRandomValues($joinableGame);
            
            $startGameResponseForCreator = new \AppBundle\WSServer\Response\StartGame();
            $startGameResponseForNewPlayer = new \AppBundle\WSServer\Response\StartGame();
            
            $startGameResponseForCreator->setPlayer($playerCreator);
            $startGameResponseForCreator->setOpponent($newPlayer);
            $startGameResponseForCreator->setIsPlayerTurn($joinableGame->getNextMovingPlayer()===$playerCreator);
            
            $startGameResponseForNewPlayer->setPlayer($newPlayer);
            $startGameResponseForNewPlayer->setOpponent($playerCreator);
            $startGameResponseForNewPlayer->setIsPlayerTurn($joinableGame->getNextMovingPlayer()===$newPlayer);
            
            $playerCreator->getConnection()->send($startGameResponseForCreator);
            $newPlayer->getConnection()->send($startGameResponseForNewPlayer);
        }


       
    }

    /**
     * 
     * @param Message $message
     * @return Player
     */
    private function prepareNewPlayer(Message $message) {
        $parameters = $message->getParameters();
        $playerBuilder = new PlayerBuilderSupervisor();
        $playerBuilder->setPlayerName($parameters['playerName']);
        $player = $playerBuilder->createPlayer(new HumanPlayerBuilder());

        $player->setConnection($message->getConnection());
        return $player;
    }
    
    private function addNewPublicGame(Message $message) {
        $creator = $this->prepareNewPlayer($message);
        $this->gameBuilder->setCreator($creator);
        $game = $this->gameBuilder->createGame(new PublicGameBuilder());
        $this->gamesRepository->attach($game);
    }

    private function joinPlayerToGame(PublicGame $game, Player $player) {
        $game->addPlayer($player);
        
    }

    public function getCommandName() {
        return 'CreateOrJoinPublicGame';
    }

    /**
     * @param array $parameters
     * @throws \Exception
     */
    public function validateParameters(array $parameters) {
        if (!isset($parameters['playerName'])) {
            throw new \Exception('CreateOrJoinPublicGame command needs playerName parameter');
        }
    }

    public function getType() {
        return WSCommandInterface::ON_MESSAGE_TYPE;
    }

}
