<?php

namespace AppBundle\Game\BoardValue;

use AppBundle\Game\Board;

/**
 * Description of BoardValueCalculator
 *
 * @author dkociuba
 */
class BoardValueCalculator {

    /**
     * @var MaxPossibleLineLengthFinder
     */
    private $maxLineLengthFinder;

    public function calculateValue(Board $board, $playerColor, $opponentColor, $movingPlayerColor = null) {
        $this->maxLineLengthFinder = new MaxPossibleLineLengthFinder($board);
        $playerLines = array();
        $playerLines = array_merge($playerLines, $this->maxLineLengthFinder->findAll(MaxPossibleLineLengthFinder::VERTICAL_DIRECTION, $playerColor));
        $playerLines = array_merge($playerLines, $this->maxLineLengthFinder->findAll(MaxPossibleLineLengthFinder::HORIZONTAL_DIRECTION, $playerColor));
        $playerLines = array_merge($playerLines, $this->maxLineLengthFinder->findAll(MaxPossibleLineLengthFinder::DESCENDING_DIRECTION, $playerColor));
        $playerLines = array_merge($playerLines, $this->maxLineLengthFinder->findAll(MaxPossibleLineLengthFinder::ASCENDING_DIRECTION, $playerColor));
        $opponentLines = array();
        $opponentLines = array_merge($opponentLines, $this->maxLineLengthFinder->findAll(MaxPossibleLineLengthFinder::VERTICAL_DIRECTION, $opponentColor));
        $opponentLines = array_merge($opponentLines, $this->maxLineLengthFinder->findAll(MaxPossibleLineLengthFinder::HORIZONTAL_DIRECTION, $opponentColor));
        $opponentLines = array_merge($opponentLines, $this->maxLineLengthFinder->findAll(MaxPossibleLineLengthFinder::DESCENDING_DIRECTION, $opponentColor));
        $opponentLines = array_merge($opponentLines, $this->maxLineLengthFinder->findAll(MaxPossibleLineLengthFinder::ASCENDING_DIRECTION, $opponentColor));

        $isPlayerMove = ($playerColor !== $movingPlayerColor);
        $isOpponentMove = ($opponentColor !== $movingPlayerColor);
        return $this->calculateValueOfLines($playerLines, $isPlayerMove) - $this->calculateValueOfLines($opponentLines, $isOpponentMove);
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
            echo "czworka";
            return 50000; //almost finish of game
        }
        if ($line->length == 4) {
            $value = pow($line->length, 4);
        } else {
            $value = pow($line->length, 3);
        }
        if ($line->type == Line::TWO_SIDE_OPEN) {
            $value *=2;
        }
        return $value;
    }

}
