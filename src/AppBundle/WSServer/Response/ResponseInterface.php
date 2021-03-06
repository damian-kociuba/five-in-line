<?php

namespace AppBundle\WSServer\Response;

/**
 * @author dkociuba
 */
interface ResponseInterface {

    public function getData();

    public function getName();
    
    public function __toString();
    
    public function getAsString();
}
