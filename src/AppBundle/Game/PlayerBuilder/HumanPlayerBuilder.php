<?php

namespace AppBundle\Game\PlayerBuilder;

use AppBundle\Game\Player;

/**
 * Description of HumanPlayerBuilder
 *
 * @author dkociuba
 */
class HumanPlayerBuilder implements PlayerBuilderInterface {

    private $name;

    /**
     * @return Player
     */
    public function build() {
        $player = new Player();
        $player->setName($this->name);
        return $player;
    }

    public function setName($name) {
        $this->name = $name;
    }

}
