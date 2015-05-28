<?php

namespace AppBundle\Game;

/**
 * Description of Game
 *
 * @author dkociuba
 */
class Game {

    /**
     * @var Player[]
     */
    private $players = array();

    /**
     *
     * @var int;
     */
    private $nextMovingPlayerIndex;

    /**
     * @var Board
     */
    private $board;

    public function __construct($boardWidth, $boardHeight) {
        $this->board = new Board($boardWidth, $boardHeight);
    }

    public function addPlayer($player) {
        $this->players[] = $player;
    }

    /**
     * @return Player[]
     */
    public function getPlayers() {
        return $this->players;
    }

    /**
     * 
     * @return Player
     */
    public function getNextMovingPlayer() {
        if($this->nextMovingPlayerIndex === null) {
            throw new \Exception('Next moving player is not defined. Did you forget use setFirstMovePlayer method?');
        }
        return $this->players[$this->nextMovingPlayerIndex];
    }

    public function setFirstMovePlayer(Player $firstMovePlayer) {
        foreach ($this->players as $index => $player) {
            if ($player === $firstMovePlayer) {
                $this->nextMovingPlayerIndex = $index;
                return;
            }
        }
        throw new \Exception('Function setFirstMovePlayer: given player not exists in players array');
    }

    public function changePlayerTurn() {
        $playersNumber = count($this->players);
        $this->nextMovingPlayerIndex = ($this->nextMovingPlayerIndex + 1) % $playersNumber;
    }

    /**
     * @return Board
     */
    public function getBoard() {
        return $this->board;
    }

}
