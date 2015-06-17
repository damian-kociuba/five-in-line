<?php

namespace AppBundle\Game;

use AppBundle\Game\Game;

/**
 * Description of GameInitializer
 *
 * @author dkociuba
 */
class GameInitializer {

    public function initializeByRandomValues(Game $game) {
        $players = $game->getPlayers();
        if (count($players) < 2) {
            throw new \Exception('Game to initialize have to have all players');
        }
        $this->setRandomColors($players);
        $this->setFirstMoveToWhite($game, $players);
    }

    private function setRandomColors(array &$players) {
        if (rand(0, 1) == 1) {
            $players[0]->setColor('black');
            $players[1]->setColor('white');
        } else {
            $players[1]->setColor('black');
            $players[0]->setColor('white');
        }
    }

    private function setFirstMoveToWhite(Game $game, array &$players) {
        foreach ($players as $player) {
            if ($player->getColor() === 'white') {
                $game->setFirstMovePlayer($player);
            }
        }
    }

}
