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
        $timer = new Timer();
        $timer->start();
        
        $this->gameTreeRoot->setValue($this->board);
        $boardValue = $this->boardValueCalculator->calculateValue($this->board, $this->movingPlayer->getColor(), $this->opponent->getColor());
        $this->gameTreeRoot->getValue()->setValue($boardValue);
        echo 'drzewa liście: ' . count($this->gameTreeRoot->getLeaves()) . "\n";
        $this->prepareNextLevelOfGameTree($this->gameTreeRoot, $this->movingPlayer, $this->opponent);
        echo 'drzewa liście: ' . count($this->gameTreeRoot->getLeaves()) . "\n";
        $this->prepareNextLevelOfGameTree($this->gameTreeRoot, $this->opponent, $this->movingPlayer);
        echo 'drzewa liście: ' . count($this->gameTreeRoot->getLeaves()) . "\n";

        $this->deleteAllNotBestLeaves($this->gameTreeRoot);
        echo 'drzewa liście: ' . count($this->gameTreeRoot->getLeaves()) . "\n";
//        $this->deleteTheWorstLeaves($this->gameTreeRoot);
//        echo 'drzewa liście: ' . count($this->gameTreeRoot->getLeaves()) . "\n";


        $leaves = $this->gameTreeRoot->getLeaves();
        echo 'pobrano liscie: ' . count($leaves);
        foreach ($leaves as $i => $leaf) {
            $value = $this->calculateBranchValue($leaf);
            $values[$i] = $value;
        }
        //var_dump($values);
        $max = $values[0];
        $maxI = 0;
        foreach ($values as $i => $value) {
            if ($value > $max) {
                $max = $value;
                $maxI = $i;
            }
        }
        echo "Max $max \n";
        $node = $leaves[$maxI];
        echo $node->getValue()->getAsString();
        while ($node->getParent() !== $this->gameTreeRoot) {
            $node = $node->getParent();
        }

        $timer->stop();
        echo 'getNextMove: ' . $timer->getResult() . "\n";
        echo 'sum of calculations: ' . BoardValueCalculator::$timer->getResult() . " / " . BoardValueCalculator::$calcCounter . "\n  ";
        BoardValueCalculator::$timer->reset();
        BoardValueCalculator::$calcCounter = 0;
        return $node->getValueOfEdgeToParent();
        //return $theBestMove;
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
                $needsToCheck = false;
                for ($sx = max(0, $x - 1); $sx < min($this->board->getWidth(), $x + 2); $sx++) {
                    for ($sy = max(0, $y - 1); $sy < min($this->board->getHeight(), $y + 2); $sy++) {
                        if ($board->getByXY($sx, $sy) !== null) {
                            $needsToCheck = true;
                        }
                    }
                }
                if (!$needsToCheck) {
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

    /**
     * @deprecated 
     * @param TreeNode $node
     */
    private function deleteTheWorstLeaves(TreeNode $node) {
        $leaves = $node->getLeaves();
        $fun = function (TreeNode $a, TreeNode $b) {
            return $a->getValue()->getValue() - $b->getValue()->getValue();
        };
        usort($leaves, $fun);

        $leavesToDelete = array_slice($leaves, 0, max((int) (count($leaves) * 0.90), count($leaves) - 10));
        foreach ($leavesToDelete as $leaf) {
            $this->deleteLeaf($leaf);
        }
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

    private function deleteAllNotBestLeaves(TreeNode $node) {
        if ($node->isLeaf()) {
            return;
        }
        $children = $node->getChildren();
        $leavesOfBranch = array();
        foreach ($children as $child) {
            if ($child->isLeaf()) {
                $leavesOfBranch[] = $child;
            } else {
                $this->deleteAllNotBestLeaves($child);
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
                $sum += abs($value);
            } else {
                $sum -= abs($value);
            }
        }

        return $sum;
    }

}
