<?php

namespace AppBundle\Tests\Storage;

/**
 * Description of ElementClassMock
 *
 * @author dkociuba
 */
class ElementClassMock {
    private $key;
    
    public function getKey() {
        return $this->key;
    }
    
    public function setKey($value) {
        $this->key = $value;
    }
}
