<?php

namespace AppBundle\WSServer\Command;

use AppBundle\WSServer\Message;

/**
 * @author dkociuba
 * 
 */
class RefreshConnection implements WSCommandInterface {

    public function getCommandName() {
        return 'RefreshConnection';
    }

    public function run(Message $message) {
        //nothing
    }

    public function validateParameters(array $parameters) {
        //nothing
    }

    public function getType() {
        return WSCommandInterface::ON_MESSAGE_TYPE;
    }

}
