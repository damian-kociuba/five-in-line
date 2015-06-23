<?php

namespace AppBundle\Game\PlayerBuilder;

use AppBundle\Game\Player;

/**
 * Description of HumanPlayerBuilder
 *
 * @author dkociuba
 */
class HumanPlayerBuilder extends PlayerBuilder {

    /**
     * @return Player
     */
    public function build() {
        $player = new Player();
        $player->setName($this->name);
        return $player;
    }

}
