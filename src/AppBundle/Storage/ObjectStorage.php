<?php

namespace AppBundle\Storage;

/**
 * @author dkociuba
 */
class ObjectStorage extends \SplObjectStorage {

    public function findFirstBy(array $criteria) {
        foreach ($this as $element) {
            if ($this->checkElementMatchToCriteria($element, $criteria)) {
                return $element;
            }
        }
        return null;
    }

    private function checkElementMatchToCriteria($element, array $criteria) {
        foreach ($criteria as $key => $value) {
            if (!isset($element->$key)) {
                return false;
            }
            if ($element->$key !== $value) {
                return false;
            }
        }
        return true;
    }

}
