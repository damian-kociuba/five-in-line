<?php

namespace AppBundle\Game;

use AppBundle\Game\PlayerBuilder\PlayerBuilder;

/**
 * Description of PlayerBuilder
 *
 * @author dkociuba
 */
class PlayerBuilderSupervisor {

    private $name;

    public function setPlayerName($name) {
        $this->name = $name;
    }

    /**
     * @param PlayerBuilder $builder
     * @return type
     */
    public function createPlayer(PlayerBuilder $builder) {
        $builder->setName($this->name);
        $player = $builder->build();
        return $player;
    }

}
