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

    public function calculateValue(Board $board, $playerColor, $opponentColor) {
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
        return $this->calculateValueOfLines($playerLines) - $this->calculateValueOfLines($opponentLines);
    }

    private function calculateValueOfLines(array $lines) {
        $sum = 0;
        foreach ($lines as $line) {
            $sum += $this->calculateValueOfLine($line);
        }
        return $sum;
    }

    private function calculateValueOfLine(Line $line) {
        if ($line->length == MaxPossibleLineLengthFinder::MIN_LINE_LENGTH) {
            return 100000; //finish of game
        }
        $value = pow($line->length, 3);
        if ($line->type == Line::TWO_SIDE_OPEN) {
            $value *=2;
        }
        return $value;
    }

}
