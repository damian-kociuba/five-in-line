<?php

namespace AppBundle\Game\BoardValue;

use AppBundle\Game\Board;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-05-27 at 11:05:16.
 */
class BoardValueCalculatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var BoardValueCalculator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new BoardValueCalculator();
    }

    /**
     * @covers AppBundle\Game\BoardValue\BoardValueCalculator::calculateValue
     */
    public function testCalculateValueWhenOpponentHas4LengthLine() {
//        //next moviung player is black
        $board = $this->getExampleBoard();
        $this->assertEquals(-50015, $this->object->calculateValue($board, 'black', 'white'));
    }
    /**
     * @covers AppBundle\Game\BoardValue\BoardValueCalculator::calculateValue
     */
    public function testCalculateValueWhenPlayerHas4LengthLine() {
        $board = $this->getExampleBoard();
        $this->assertEquals(50015, $this->object->calculateValue($board, 'white', 'black'));
    }
    /**
     * @covers AppBundle\Game\BoardValue\BoardValueCalculator::calculateValue
     */
    public function testCalculateValueWhenPlayerHas5LengthLine() {
        $board = $this->getExampleBoard();
        //black has 37 points board
        $this->assertEquals(99967, $this->object->calculateValue($board, 'green', 'black'));
    }
    
    /**
     * @covers AppBundle\Game\BoardValue\BoardValueCalculator::calculateValue
     */
    public function testCalculateValueWhenPlayerHas3LengthLine() {
        $board = $this->getExampleBoard2();
        //black has 37 points board
        $this->assertEquals(-36, $this->object->calculateValue($board, 'black', 'white'));
    }
    /**
     * @covers AppBundle\Game\BoardValue\BoardValueCalculator::calculateValue
     */
    public function testCalculateValueWhenPlayerBlock3LengthLine() {
        $this->markTestSkipped();
        $board = $this->getExampleBoard3();
        
        //black has 37 points board
        $this->assertEquals(8, $this->object->calculateValue($board, 'black', 'white'));
    }

    private function getExampleBoard() {
        $board = new \AppBundle\Game\Board(8, 8);
        /*
          .......*
          .......*
          ..O....*
          ...O...*
          ....O..*
          ..#..O..
          .#...O..
          #....O..
         * O - white
         * # - black
         */
        $board->markField(2, 2, 'white');
        $board->markField(3, 3, 'white');
        $board->markField(4, 4, 'white');
        $board->markField(5, 5, 'white');
        $board->markField(5, 6, 'white');
        $board->markField(5, 7, 'white');

        $board->markField(2, 5, 'black');
        $board->markField(1, 6, 'black');
        $board->markField(0, 7, 'black');
        
        $board->markField(7, 0, 'green');
        $board->markField(7, 1, 'green');
        $board->markField(7, 2, 'green');
        $board->markField(7, 3, 'green');
        $board->markField(7, 4, 'green');
        return $board;
    }
    private function getExampleBoard2() {
        $board = new \AppBundle\Game\Board(8, 8);
        /*
          ........
          ......O.
          ......O.
          ......O.
          .#......
          .#......
          ..#.....
          ........
         * O - white
         * # - black
         */
        $board->markField(6, 1, 'white');
        $board->markField(6, 2, 'white');
        $board->markField(6, 3, 'white');

        $board->markField(1, 4, 'black');
        $board->markField(1, 5, 'black');
        $board->markField(2, 6, 'black');
        
        return $board;
    }
    private function getExampleBoard3() {
        $board = new \AppBundle\Game\Board(8, 8);
        /*
          ........
          ........
          ......O.
          ......O.
          ......O.
          .#....#.
          .#......
          ........
         * O - white
         * # - black
         */
        $board->markField(6, 2, 'white');
        $board->markField(6, 3, 'white');
        $board->markField(6, 4, 'white');
        $board->markField(6, 5, 'black');

        $board->markField(1, 5, 'black');
        $board->markField(1, 6, 'black');
        
        return $board;
    }


}
