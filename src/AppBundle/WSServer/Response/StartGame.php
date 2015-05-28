<?php

namespace AppBundle\WSServer\Response;

use AppBundle\Game\Player;

/**
 * Description of StartGame
 *
 * @author dkociuba
 */
class StartGame extends Response {

    /**
     * @var Player
     */
    private $player;

    /**
     * @var Player
     */
    private $opponent;

    /**
     * @var boolean
     */
    private $isPlayerTurn;

    public function getData() {
        return array(
            'playerColor' => $this->player->getColor(),
            'isPlayerTurn' => $this->isPlayerTurn,
            'opponentName' => $this->opponent->getName()
        );
    }

    public function getName() {
        return 'StartGame';
    }

    /**
     * 
     * @param Player $player
     */
    public function setPlayer(Player $player) {
        $this->player = $player;
    }

    /**
     * 
     * @param Player $opponent
     */
    public function setOpponent(Player $opponent) {
        $this->opponent = $opponent;
    }

    /**
     * 
     * @param boolean $isPlayerTurn
     */
    public function setIsPlayerTurn($isPlayerTurn) {
        $this->isPlayerTurn = $isPlayerTurn;
    }
}
