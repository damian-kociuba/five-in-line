<?php

namespace AppBundle\WSServer\Command;

/**
 * @author dkociuba
 */
class CreatePrivateGame implements WSCommandInterface {
    public function run($user, array $parameters) {
        
    }

    public function getCommandName() {
        return 'CreatePrivateGame';
    }

}
