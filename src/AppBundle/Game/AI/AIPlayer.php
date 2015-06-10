<?php

namespace AppBundle\Game\AI;

use Ratchet\ConnectionInterface;
use AppBundle\Game\Player;
use AppBundle\Game\AI\MoveMaker;
use AppBundle\Game\Board;

/**
 * @author dkociuba
 */
class AIPlayer extends \AppBundle\Game\Player {

    /**
     * @var Player
     */
    private $opponent;
    
    /**
     * @var Board
     */
    private $board;

    
    public function setOpponent(Player $oppontent) {
        $this->opponent = $oppontent;
    }
    
    public function setBoard($board) {
        $this->board = $board;
    }
    
    public function makeMove() {
        $moveMaker = new MoveMaker($this->board, $this, $this->opponent);
        $move = $moveMaker->getNextMoveCoordinate();
        $x = $move['x'];
        $y = $move['y'];
        $this->board->markField($x, $y, $this->getColor());
        $this->opponent->getConnection()->send(json_encode(array(
            'command' => 'MoveMade',
            'parameters' => array(
                'color' => 'black',
                'x' => $x,
                'y' => $y,
                'isPlayerTurn' => true,
            )
        )));
    }
}
