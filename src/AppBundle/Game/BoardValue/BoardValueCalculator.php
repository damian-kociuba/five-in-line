<?php

namespace AppBundle\Game\BoardValue;

use AppBundle\Game\Board;
use AppBundle\Benchmark\Timer;

/**
 * Description of BoardValueCalculator
 *
 * @author dkociuba
 */
class BoardValueCalculator {

    static public $calcCounter = 0;
    static public $timer = null;

    /**
     * @var MaxPossibleLineLengthFinder
     */
    private $maxLineLengthFinder;

    public function __construct() {
        if (self::$timer === null) {
            self::$timer = new Timer();
        }
    }

    public function calculateValue(Board $board, $playerColor, $opponentColor, $movingPlayerColor = null) {
        self::$timer->continueCounting();
        self::$calcCounter++;
        $this->maxLineLengthFinder = new MaxPossibleLineLengthFinder($board);
        $playerLines = $this->maxLineLengthFinder->findAll($playerColor);
        $opponentLines = $this->maxLineLengthFinder->findAll($opponentColor);

        $isPlayerMove = ($playerColor !== $movingPlayerColor);
        $isOpponentMove = ($opponentColor !== $movingPlayerColor);
        
        $value = $this->calculateValueOfLines($playerLines, $isPlayerMove) - $this->calculateValueOfLines($opponentLines, $isOpponentMove);
        
        self::$timer->stop();
        return $value;
    }

    private function calculateValueOfLines(array $lines, $moveNext = false) {
        $sum = 0;
        foreach ($lines as $line) {
            $sum += $this->calculateValueOfLine($line, $moveNext);
        }
        return $sum;
    }

    private function calculateValueOfLine(Line $line, $moveNext = false) {
        if ($line->length == MaxPossibleLineLengthFinder::MIN_LINE_LENGTH) {
            return 100000; //finish of game
        }
        if ($moveNext && $line->length == MaxPossibleLineLengthFinder::MIN_LINE_LENGTH - 1) {
            return 50000; //almost finish of game
        }
        if ($line->length == 4) {
            $value = 256; //4^4
        } else {
            $value = pow($line->length, 3);
        }
        if ($line->type == Line::TWO_SIDE_OPEN) {
            $value *=2;
        }
        return $value;
    }

}
