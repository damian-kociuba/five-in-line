<?php

namespace AppBundle;

/**
 * Description of ConfigContainer
 *
 * @author dkociuba
 */
class ConfigContainer {

    /**
     * @var array
     */
    private $values = array();

    public function __construct(array $configValues) {
        $this->values = $configValues;
    }
    
    public function getValue($name) {
        if(! isset($this->values[$name])) {
            throw new \Exception('There is no value in config named '.$name);
        }
        
        return $this->values[$name];
    }

}
