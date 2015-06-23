<?php

namespace AppBundle\Game\PlayerBuilder;

use AppBundle\Game\AI\AIPlayer;

/**
 * Description of AIPlayerBuilder
 *
 * @author dkociuba
 */
class AIPlayerBuilder extends PlayerBuilder {

    /**
     * @return AIPlayer
     */
    public function build() {
        $player = new AIPlayer();
        $player->setName($this->name);
        $player->setConnection(new \AppBundle\WSServer\ArtificialConnection());
        return $player;
    }
}
