<?php

namespace AppBundle\Game;

/**
 * Description of Judge
 *
 * @author dkociuba
 */
class Judge {

    const FIRST_PLAYER_WIN = 1;
    const SECOND_PLAYER_WIN = 2;
    const DRAW = 3;
    const CONTINUE_PLAYING = 4;

    public function check(Board $board, Player $firstPlayer, Player $secondPlayer) {

        $paterns = array(
            'horizontalLine' => array(
                array(1, 1, 1, 1, 1)
            ),
            'verticalLine' => array(
                array(1),
                array(1),
                array(1),
                array(1),
                array(1)
            ),
            'increasingLine' => array(
                array(null, null, null, null, 1),
                array(null, null, null, 1, null),
                array(null, null, 1, null, null),
                array(null, 1, null, null, null),
                array(1, null, null, null, null)
            ),
            'decreasingLine' => array(
                array(1, null, null, null, null),
                array(null, 1, null, null, null),
                array(null, null, 1, null, null),
                array(null, null, null, 1, null),
                array(null, null, null, null, 1)
            )
        );

        foreach ($paterns as $onePatern) {
            $onePaternForFirstPlayer = $this->createPaternBoard($onePatern, $firstPlayer->getColor());
            $onePaternForSecondPlayer = $this->createPaternBoard($onePatern, $secondPlayer->getColor());

            if ($this->findMartixPaternOnBoard($onePaternForFirstPlayer, $board)) {
                return self::FIRST_PLAYER_WIN;
            }
            if ($this->findMartixPaternOnBoard($onePaternForSecondPlayer, $board)) {
                return self::SECOND_PLAYER_WIN;
            }
        }
        
        if($this->checkDraw($board)) {
            return self::DRAW;
        }
        return self::CONTINUE_PLAYING;
    }

    /**
     * 
     * @param array $arrayPatern 2-dimension rectangular array of null/notNull values
     * @param type $color
     */
    private function createPaternBoard(array $arrayPatern, $color) {
        $height = count($arrayPatern);
        $width = count($arrayPatern[0]);

        $patern = new Board($width, $height);
        foreach ($arrayPatern as $y => $row) {
            foreach ($row as $x => $field) {
                if ($field === null) {
                    continue;
                }
                $patern->markField($x, $y, $color);
            }
        }

        return $patern;
    }

    private function findMartixPaternOnBoard(Board $matrixPatern, Board $board) {
        $boardWidth = $board->getWidth();
        $boardHeight = $board->getHeight();

        for ($x = 0; $x < $boardWidth - $matrixPatern->getWidth() + 1; $x++) {
            for ($y = 0; $y < $boardHeight - $matrixPatern->getHeight() + 1; $y++) {
                $result = $this->checkPaternForPlace($matrixPatern, $board, $x, $y);
                if ($result) {
                    return true;
                }
            }
        }
        return false;
    }

    private function checkPaternForPlace(Board $matrixPatern, Board $board, $offsetX, $offsetY) {
        $rawPaternMatrix = $matrixPatern->getAllRawFields();
        $x = $y = 0;
        foreach ($rawPaternMatrix as $y => $patternRow) {
            foreach ($patternRow as $x => $paternField) {
                if ($paternField === null) {
                    continue;
                }
                if ($paternField !== $board->getByXY($offsetX + $x, $offsetY + $y)) {
                    return false;
                }
                $x++;
            }
            $y++;
        }
        return true;
    }

    private function checkDraw(Board $board) {
        $rawBoard = $board->getAllRawFields();
        foreach($rawBoard as $row) {
            foreach($row as $field) {
                if($field === null) { //there is free field, so it's not draw
                    return false;
                }
            }
        }
        return true;
    }
}
