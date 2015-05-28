<?php

namespace AppBundle\WSServer\Response;

/**
 * Description of Response
 *
 * @author dkociuba
 */
abstract class Response implements ResponseInterface {

    public function __toString() {
        return $this->getAsString();
    }

    public function getAsString() {
        return json_encode($this->getAsArray());
    }
    public function getAsArray() {
        return array(
            'command' => $this->getName(),
            'parameters' => $this->getData()
        );
    }

}
