<?php

namespace AppBundle\Game\AI;

use AppBundle\Game\Board;
use AppBundle\Game\Player;
use AppBundle\Game\BoardValue\BoardValueCalculator;

/**
 * Consist algoritm to make decision where put next pawn
 *
 * @author dkociuba
 */
class MoveMaker {

    /**
     * @var Board
     */
    private $board;

    /**
     * @var Player
     */
    private $movingPlayer;

    /**
     * @var Player
     */
    private $opponent;

    /**
     *
     * @var BoardValueCalculator
     */
    private $boardValueCalculator;

    /**
     * @param Board $board
     * @param Player $movingPlayer
     * @param Player $opponent
     */
    public function __construct(Board $board, Player $movingPlayer, Player $opponent) {
        $this->board = $board;
        $this->movingPlayer = $movingPlayer;
        $this->opponent = $opponent;
        $this->boardValueCalculator = new BoardValueCalculator();
    }

    public function getNextMoveCoordinate() {
        $maxValueOfBoard = -1000000;
        $possibleSituations = array();
        $theBestMove = array();
        for ($x = 0; $x < $this->board->getWidth(); $x++) {
            for ($y = 0; $y < $this->board->getHeight(); $y++) {
                if ($this->board->getByXY($x, $y) !== null) { //non empty fields are ommited
                    continue;
                }
                $newBoard = clone $this->board;
                $newBoard->markField($x, $y, $this->movingPlayer->getColor());

                $possibleSituations[] = $newBoard;

                $value = $this->boardValueCalculator->calculateValue($newBoard,  $this->movingPlayer->getColor(), $this->opponent->getColor());
                
                if ($value > $maxValueOfBoard) {
                    $maxValueOfBoard = $value;
                    $theBestMove = array('x'=>$x, 'y'=>$y);
                }
            }
        }
        echo 'Wartosc planszy: '.$maxValueOfBoard."\n";
        return $theBestMove;

        /* foreach($possibleSituations as $onePosibleSituation) {
          $value = $this->boardValueCalculator->calculateValue();
          if($value>$maxValueOfBoard) {
          $maxValueOfBoard = $value;
          $theBestMove
          }
          } */
    }

}
