<?php

namespace AppBundle\WSServer\Command;

/**
 *
 * @author dkociuba
 */
interface WSCommandInterface {

    public function getCommandName();

    public function validateParameters(array $parameters);
}
