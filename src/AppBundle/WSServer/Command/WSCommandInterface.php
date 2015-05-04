<?php

namespace AppBundle\WSServer\Command;

use AppBundle\WSServer\Message;
/**
 *
 * @author dkociuba
 */
interface WSCommandInterface {

    public function getCommandName();

    public function validateParameters(array $parameters);
    
    public function run(Message $message);
}
