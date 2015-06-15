<?php

namespace AppBundle\Game\GameBuilder;

use AppBundle\Game\Game;
use AppBundle\ConfigContainer;
/**
 *
 * @author dkociuba
 */
interface GameBuilderInterface {

    public function setConfig(ConfigContainer $config);

    /**
     * @return Game 
     */
    public function build();
}
