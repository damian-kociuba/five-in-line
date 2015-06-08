<?php

namespace AppBundle\Game\BoardValue;

use AppBundle\Game\Board;

/**
 * Description of MaxLineLengthFinder
 *
 * @author dkociuba
 */
class MaxPossibleLineLengthFinder {

    const HORIZONTAL_DIRECTION = 0;
    const VERTICAL_DIRECTION = 1;
    const ASCENDING_DIRECTION = 2;
    const DESCENDING_DIRECTION = 3;
    const MIN_LINE_LENGTH = 5;

    /**
     * @var Board
     */
    private $board;

    /**
     * @param Board $board
     */
    public function __construct(Board $board) {
        $this->board = $board;
    }

    public function findAll($direction, $color) {
        $lines = array();
        for ($x = 0; $x < $this->board->getWidth(); $x++) {
            for ($y = 0; $y < $this->board->getHeight(); $y++) {
                switch ($direction) {
                    case self::HORIZONTAL_DIRECTION: $line = $this->findForHorizontalLine($x, $y, $color);
                        break;
                    case self::VERTICAL_DIRECTION: $line = $this->findForVerticalLine($x, $y, $color);
                        break;
                    case self::ASCENDING_DIRECTION: $line = $this->findForAscendingLine($x, $y, $color);
                        break;
                    case self::DESCENDING_DIRECTION: $line = $this->findForDescendingLine($x, $y, $color);
                        break;
                }
                if ($line !== null) {
                    $lines[] = $line;
                }
            }
        }
        return $lines;
    }

    private function findForHorizontalLine($startX, $startY, $color) {
        $length = 0;
        while ($startX + $length< $this->board->getWidth() && $this->board->getByXY($startX + $length, $startY) == $color) {
            $length++;
        }
        if ($length == 0) {
            return;
        }
        //looking for next free places
        $nextFreePlayces = 0;
        for ($i = $length; $i <= self::MIN_LINE_LENGTH; $i++) {
            if ($startX + $i >= $this->board->getWidth()) {
                break;
            }
            if ($this->board->getByXY($startX + $i, $startY) === $color || $this->board->getByXY($startX + $i, $startY) === null) {
                $nextFreePlayces++;
            } else {
                break;
            }
        }

        //looking for back free places
        $backFreePlayces = 0;
        for ($i = 1; $i <= self::MIN_LINE_LENGTH - $length; $i++) {
            if ($startX - $i < 0) {
                break;
            }
            if ($this->board->getByXY($startX - $i, $startY) === $color) {
                return;
            }
            if ($this->board->getByXY($startX - $i, $startY) === null) {
                $backFreePlayces++;
            } else {
                break;
            }
        }
        if ($length + $nextFreePlayces + $backFreePlayces < self::MIN_LINE_LENGTH) {
            return null;
        }
        $line = new Line();
        $line->length = $length;
        if ($backFreePlayces == 0 || $nextFreePlayces == 0) {
            $line->type = Line::ONE_SIDE_OPEN;
        } else {
            $line->type = Line::TWO_SIDE_OPEN;
        }
        return $line;
    }

    private function findForVerticalLine($startX, $startY, $color) {

        $length = 0;
        while ($startY + $length < $this->board->getHeight() && $this->board->getByXY($startX, $startY + $length) == $color) {
            $length++;
        }
        if ($length == 0) {
            return;
        }
        //looking for next free places
        $nextFreePlayces = 0;
        for ($i = $length; $i <= self::MIN_LINE_LENGTH; $i++) {
            if ($startY + $i >= $this->board->getHeight()) {
                break;
            }
            if ($this->board->getByXY($startX, $startY + $i) === $color || $this->board->getByXY($startX, $startY + $i) === null) {
                $nextFreePlayces++;
            } else {
                break;
            }
        }

        //looking for back free places
        $backFreePlayces = 0;
        for ($i = 1; $i <= self::MIN_LINE_LENGTH - $length; $i++) {
            if ($startY - $i < 0) {
                break;
            }
            if ($this->board->getByXY($startX, $startY - $i) === $color) {
                return;
            }
            if ($this->board->getByXY($startX, $startY - $i) === null) {
                $backFreePlayces++;
            } else {
                break;
            }
        }
        if ($length + $nextFreePlayces + $backFreePlayces < self::MIN_LINE_LENGTH) {
            return null;
        }
        $line = new Line();
        $line->length = $length;
        if ($backFreePlayces == 0 || $nextFreePlayces == 0) {
            $line->type = Line::ONE_SIDE_OPEN;
        } else {
            $line->type = Line::TWO_SIDE_OPEN;
        }
        return $line;
    }

    private function findForAscendingLine($startX, $startY, $color) {
        $length = 0;
        while ($startX + $length< $this->board->getWidth() && $startY - $length >= 0 && $this->board->getByXY($startX + $length, $startY - $length) == $color) {
            $length++;
        }
        if ($length == 0) {
            return;
        }
        //looking for next free places
        $nextFreePlayces = 0;
        for ($i = $length; $i <= self::MIN_LINE_LENGTH; $i++) {
            if ($startX + $i >= $this->board->getWidth()) {
                break;
            }
            if ($startY - $i < 0) {
                break;
            }
            if ($this->board->getByXY($startX + $i, $startY - $i) === $color || $this->board->getByXY($startX + $i, $startY - $i) === null) {
                $nextFreePlayces++;
            } else {
                break;
            }
        }

        //looking for back free places
        $backFreePlayces = 0;
        for ($i = 1; $i <= self::MIN_LINE_LENGTH - $length; $i++) {
            if ($startX - $i < 0) {
                break;
            }
            if ($startY + $i >= $this->board->getHeight()) {
                break;
            }
            if ($this->board->getByXY($startX - $i, $startY + $i) === $color) {
                return;
            }
            if ($this->board->getByXY($startX - $i, $startY + $i) === null) {
                $backFreePlayces++;
            } else {
                break;
            }
        }
        
        if ($length + $nextFreePlayces + $backFreePlayces < self::MIN_LINE_LENGTH) {
            return null;
        }
        $line = new Line();
        $line->length = $length;
        if ($backFreePlayces == 0 || $nextFreePlayces == 0) {
            $line->type = Line::ONE_SIDE_OPEN;
        } else {
            $line->type = Line::TWO_SIDE_OPEN;
        }

        return $line;
    }

    private function findForDescendingLine($startX, $startY, $color) {
        $length = 0;
        while ($startX + $length < $this->board->getWidth() && $startY + $length < $this->board->getHeight() && $this->board->getByXY($startX + $length, $startY + $length) == $color) {
            $length++;
        }
        if ($length == 0) {
            return;
        }
        //looking for next free places
        $nextFreePlayces = 0;
        for ($i = $length; $i <= self::MIN_LINE_LENGTH; $i++) {
            if ($startX + $i >= $this->board->getWidth()) {
                break;
            }
            if ($startY + $i >= $this->board->getHeight()) {
                break;
            }
            if ($this->board->getByXY($startX + $i, $startY + $i) === $color || $this->board->getByXY($startX + $i, $startY + $i) === null) {
                $nextFreePlayces++;
            } else {
                break;
            }
        }

        //looking for back free places
        $backFreePlayces = 0;
        for ($i = 1; $i <= self::MIN_LINE_LENGTH - $length; $i++) {
            if ($startX - $i < 0) {
                break;
            }
            if ($startY - $i < 0) {
                break;
            }
            if ($this->board->getByXY($startX - $i, $startY - $i) === $color) {
                return;
            }
            if ($this->board->getByXY($startX - $i, $startY - $i) === null) {
                $backFreePlayces++;
            } else {
                break;
            }
        }
        if ($length + $nextFreePlayces + $backFreePlayces < self::MIN_LINE_LENGTH) {
            return null;
        }
        $line = new Line();
        $line->length = $length;
        if ($backFreePlayces == 0 || $nextFreePlayces == 0) {
            $line->type = Line::ONE_SIDE_OPEN;
        } else {
            $line->type = Line::TWO_SIDE_OPEN;
        }
        
        return $line;
                

    }

}
