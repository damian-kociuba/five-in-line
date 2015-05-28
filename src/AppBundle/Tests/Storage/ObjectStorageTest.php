<?php

namespace AppBundle\Tests\Storage;

use AppBundle\Storage\ObjectStorage;

class ObjectStorageTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ObjectStorage
     */
    private $object;

    public function setUp() {
        $this->object = new ObjectStorage();
        $element1 = new \stdClass();
        $element1->key = 'abcd';
        $element2 = new \stdClass();
        $element2->key = 'dcba';
        
        $elementWithPrivateAttribute = new ElementClassMock();
        $elementWithPrivateAttribute->setKey('privateValue');
        $this->object->attach($element1);
        $this->object->attach($element2);
        $this->object->attach($elementWithPrivateAttribute);
    }

    public function testFindFirstByWhenElementExists() {
        $criteria = array('key'=>'dcba');
        $result = $this->object->findFirstBy($criteria);
        $this->assertNotNull($result);
        $this->assertEquals('dcba', $result->key);
    }
    
    public function testFindFirstByWhenElementNonExists() {
        $criteria = array('key'=>'nonExistingValue');
        $result = $this->object->findFirstBy($criteria);
        $this->assertNull($result);
    }
    
    public function testFindFirstByWhenElementHasPrivateAttribute() {
        $criteria = array('key'=>'privateValue');
        $result = $this->object->findFirstBy($criteria);
        $this->assertNotNull($result);
        $this->assertEquals('privateValue', $result->getKey());
    }

    
}
