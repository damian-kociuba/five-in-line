<?php

namespace AppBundle\Game;

/**
 * Description of Board
 *
 * @author dkociuba
 */
class Board {

    /**
     * @var integer
     */
    private $width;

    /**
     * @var integer
     */
    private $height;

    /**
     *
     * @var type 
     */
    private $fields;

    /**
     * Value of situation on the board
     * @var number
     */
    private $value;

    public function __construct($boardWidth, $boardHeight) {
        $this->width = $boardWidth;
        $this->height = $boardHeight;

        for ($y = 0; $y < $boardHeight; $y++) {
            $this->fields[] = array_fill(0, $boardWidth, null);
        }
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getByXY($x, $y) {
        return $this->fields[$y][$x];
    }

    public function getAllRawFields() {
        return $this->fields;
    }

    public function markField($x, $y, $fieldColor) {
        $this->ValidateAreCoordinatesInBoard($x, $y);
        $this->ValidateIsFieldEmpty($x, $y);

        $this->fields[$y][$x] = $fieldColor;
    }

    /**
     * @param int $x
     * @param int $y
     * @throws \Exception
     */
    private function ValidateAreCoordinatesInBoard($x, $y) {
        if ($x < 0 || $x >= $this->width) {
            throw new \Exception('X coordinate is not valid: ' . $x);
        }
        if ($y < 0 || $y >= $this->height) {
            throw new \Exception('Y coordinate is not valid: ' . $y);
        }
    }

    private function ValidateIsFieldEmpty($x, $y) {
        if ($this->fields[$y][$x] !== null) {
            throw new \Exception("Field $x x $y is not empty");
        }
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function getAsString() {
        $string = "\n";
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                if ($this->getByXY($x, $y) === null) {
                    $string .= '.';
                } else {
                    $string .= $this->getByXY($x, $y) === 'white' ? 'O' : '#';
                }
            }
            $string .= "\n";
        }
        return $string;
    }

}
