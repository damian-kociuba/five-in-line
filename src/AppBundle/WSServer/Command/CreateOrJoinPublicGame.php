<?php

namespace AppBundle\WSServer\Command;

use AppBundle\Game\GamesRepository;
use AppBundle\WSServer\Response\PrivateGameCreated;
use AppBundle\WSServer\Message;
use AppBundle\Game\PlayerBuilder;

/**
 * @author dkociuba
 */
class CreateOrJoinPublicGame implements WSCommandInterface {

    /**
     *
     * @var GamesRepository
     */
    private $gamesRepository;

    public function __construct(GamesRepository $gamesRepository) {
        $this->gamesRepository = $gamesRepository;
    }

    public function run(Message $message) {
        $parameters = $message->getParameters();
        echo 'Create Public Game';
         $playerBuilder = new PlayerBuilder();
        
        $playerBuilder->setPlayerName($parameters['playerName']);
        $player = $playerBuilder->createPlayer(PlayerBuilder::HUMAN_PLAYER);
        $player->setConnection($message->getConnection());
        //$game = $this->gamesRepository->createPrivateGame($player);
        //$this->gamesRepository->attach($game);

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
