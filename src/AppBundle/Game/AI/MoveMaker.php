<?php

namespace AppBundle\Game\AI;

use AppBundle\Game\Board;
use AppBundle\Game\Player;
use AppBundle\Game\BoardValue\BoardValueCalculator;
use AppBundle\Storage\Tree\TreeNode;
use AppBundle\Benchmark\Timer;

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
     * @var TreeNode
     */
    private $gameTreeRoot;

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
        $this->gameTreeRoot = new TreeNode();
    }

    public function getNextMoveCoordinate() {
        $this->gameTreeRoot->setValue($this->board);
        $boardValue = $this->boardValueCalculator->calculateValue($this->board, $this->movingPlayer->getColor(), $this->opponent->getColor());
        $this->gameTreeRoot->getValue()->setValue($boardValue);

        //two moves forward
        $this->prepareNextLevelOfGameTree($this->gameTreeRoot, $this->movingPlayer, $this->opponent);
        $this->prepareNextLevelOfGameTree($this->gameTreeRoot, $this->opponent, $this->movingPlayer);

        //human opponent should choose the best move for him
        $this->leaveOnlyBestLeaves($this->gameTreeRoot);


        $leaves = $this->gameTreeRoot->getLeaves();

        //calculate value for each path from root to leaf
        foreach ($leaves as $i => $leaf) {
            $value = $this->calculateBranchValue($leaf);
            $values[$i] = $value;
        }

        //find path with maximum value
        $max = $values[0];
        $maxI = 0;
        foreach ($values as $i => $value) {
            if ($value > $max) {
                $max = $value;
                $maxI = $i;
            }
        }

        //the best leaf
        $node = $leaves[$maxI];

        echo $node->getValue()->getAsString();
        while ($node->getParent() !== $this->gameTreeRoot) {
            $node = $node->getParent();
        }

        return $node->getValueOfEdgeToParent();
    }

    private function prepareNextLevelOfGameTree(TreeNode $node, Player $movingPlayer, Player $opponent) {
        if (!$node->isLeaf()) {
            foreach ($node->getChildren() as $child) {
                $this->prepareNextLevelOfGameTree($child, $movingPlayer, $opponent);
            }
        } else {
            if ($node->isPernamentLeaf()) {
                return;
            }
            $this->addNextMoves($node, $movingPlayer, $opponent);
        }
    }

    private function addNextMoves(TreeNode $node, Player $movingPlayer) {
        $board = $node->getValue();
        $boardCalculator = new BoardValueCalculator();

        for ($x = 0; $x < $board->getWidth(); $x++) {

            for ($y = 0; $y < $board->getHeight(); $y++) {
                if ($board->getByXY($x, $y) !== null) { //non empty fields are ommited
                    continue;
                }
                
                if (!$this->isFieldNeedsToBeChecked($board, $x, $y)) {
                    continue;
                }

                $newBoard = clone $board;
                $newBoard->markField($x, $y, $movingPlayer->getColor());
                $boardValue = $boardCalculator->calculateValue($newBoard, $this->movingPlayer->getColor(), $this->opponent->getColor(), $movingPlayer->getColor());

                if ($movingPlayer == $this->opponent) {
                    $boardValue *= -1;
                }

                $newBoard->setValue($boardValue);
                $newSituation = new TreeNode();
                $newSituation->setParent($node);
                $newSituation->setValue($newBoard);
                $newSituation->setValueOfEdgeToParent(array('x' => $x, 'y' => $y));
                if (abs($boardValue) > 100000) {
                    $newSituation->markAsPernamentLeaf();
                }
                $node->addChild($newSituation);
            }
        }
    }

    private function isFieldNeedsToBeChecked(Board $board, $x, $y) {
        for ($sx = max(0, $x - 1); $sx < min($board->getWidth(), $x + 2); $sx++) {
            for ($sy = max(0, $y - 1); $sy < min($board->getHeight(), $y + 2); $sy++) {
                if ($board->getByXY($sx, $sy) !== null) {
                    return true;
                }
            }
        }
        return false;
    }

    private function deleteLeaf(TreeNode $node) {
        if (!$node->isLeaf()) {
            return;
        }
        if ($node->isPernamentLeaf()) {
            return;
        }
        if ($node->getParent() !== null) {
            $node->getParent()->removeChild($node);
            if ($node->getParent()->isLeaf()) {
                $this->deleteLeaf($node->getParent());
            }
        }
    }

    private function leaveOnlyBestLeaves(TreeNode $node) {
        if ($node->isLeaf()) {
            return;
        }
        $children = $node->getChildren();
        $leavesOfBranch = array();
        foreach ($children as $child) {
            if ($child->isLeaf()) {
                $leavesOfBranch[] = $child;
            } else {
                $this->leaveOnlyBestLeaves($child);
            }
        }
        if (empty($leavesOfBranch)) {
            return;
        }

        $maxNode = $leavesOfBranch[0];
        foreach ($leavesOfBranch as $leaf) {
            if ($leaf->getValue()->getValue() > $maxNode->getValue()->getValue()) {
                $maxNode = $leaf;
            }
        }
        foreach ($leavesOfBranch as $leaf) {
            if ($leaf !== $maxNode) {
                $this->deleteLeaf($leaf);
            }
        }
    }

    private function calculateBranchValue(TreeNode $leaf) {
        if ($leaf->getParent() === null) { //root
            return $leaf->getValue()->getValue();
        }

        $node = $leaf;
        $nodeValues = array();
        while ($node->getParent() !== null) {
            $nodeValues[] = $node->getValue()->getValue();
            $node = $node->getParent();
        }
        $nodeValues = array_reverse($nodeValues);
        $sum = 0;
        foreach ($nodeValues as $i => $value) {
            if ($i % 2 == 0) {
                $sum += ($value);
                //echo ' + ' . ($value);
            } else {
                $sum -= ($value);
                //echo ' - ' . ($value);
            }
        }
        // echo ' = ' . $sum . "\n";
        return $sum;
    }

}
