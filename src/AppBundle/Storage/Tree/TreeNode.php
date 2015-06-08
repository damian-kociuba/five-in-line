<?php

namespace AppBundle\Storage\Tree;

/**
 * Description of TreeNode
 *
 * @author dkociuba
 */
class TreeNode {

    /**
     *
     * @var TreeNode
     */
    private $parent;

    /**
     *
     * @var \SplObjectStorage
     */
    private $children;

    private $value;
    
    private $valueOfEdgeToParent;
    
    private $isPernamentLeaf = false;
    
    public function __construct() {
        $this->children = new \SplObjectStorage();
    }
    public function setParent(TreeNode $parent) {
        $this->parent = $parent;
    }

    /**
     * @return TreeNode
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param TreeNode $child
     */
    public function addChild(TreeNode $child) {
        $this->children->attach($child);
    }

    /**
     * 
     * @return \SplObjectStorage
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * @param TreeNode $child
     */
    public function removeChild(TreeNode $child) {
        if($this->isPernamentLeaf) {
            throw new \Execption('Cannot add child to pernament leaf');
        }
        $this->children->detach($child);
    }

    public function setValue($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function setValueOfEdgeToParent($valueOfEdgeToParent) {
        $this->valueOfEdgeToParent = $valueOfEdgeToParent;
    }
    
    public function getValueOfEdgeToParent() {
        var_dump($this->valueOfEdgeToParent);
        var_dump($this->value->getValue());
        return $this->valueOfEdgeToParent;
    }
    
    public function isLeaf() {
        return $this->getChildren()->count() == 0;
    }
    
    public function getLeaves() {
        $leaves = array();
        if($this->isLeaf()) {
            return array($this);
        }
        foreach($this->getChildren() as $child) {
            $leaves = array_merge($leaves, $child->getLeaves());
        }
        return $leaves;
    }
    
    public function markAsPernamentLeaf() {
        $this->isPernamentLeaf = true;
        $this->children->removeAll($this->children);
    }
    
    public function isPernamentLeaf() {
        return $this->isPernamentLeaf;
    }
}
