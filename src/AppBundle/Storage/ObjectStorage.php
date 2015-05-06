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
        foreach ($criteria as $attribute => $value) {
            if(!$this->checkElementByPlainAttribute($element, $attribute, $value) && 
                    !$this->checkElementByGetterAttribute($element, $attribute, $value)) {
                return false;
            }
        }
        return true;
    }

    private function checkElementByPlainAttribute($element, $attribute, $value) {
        if (!isset($element->$attribute)) {
            return false;
        }
        if ($element->$attribute !== $value) {
            return false;
        }
        return true;
    }

    private function checkElementByGetterAttribute($element, $attribute, $value) {
        $getter = 'get'.ucfirst($attribute);
        if(!method_exists($element, $getter)) {
            return false;
        }
        
        if($element->$getter() !== $value) {
            return false;
        }
        
        return true;
    }

}
