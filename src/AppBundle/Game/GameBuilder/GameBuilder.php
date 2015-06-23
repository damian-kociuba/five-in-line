<?php

namespace AppBundle\Game\GameBuilder;

use AppBundle\Game\Game;
use AppBundle\ConfigContainer;

/**
 *
 * @author dkociuba
 */
abstract class GameBuilder {

    /**
     * @var ConfigContainer
     */
    protected $config;

    public function setConfig(ConfigContainer $config) {
        $this->config = $config;
    }

    /**
     * @return Game 
     */
    public abstract function build();
}
