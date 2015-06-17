<?php

namespace AppBundle\Game;

use AppBundle\Game\GamesRepository;
use AppBundle\Game\GameBuilder;

/**
 * Description of PublicGamesManager
 *
 * @author dkociuba
 */
class PublicGamesManager {

    /**
     *
     * @var GamesRepository
     */
    private $gameRepository;

    public function __construct(GamesRepository $gameRepository) {
        $this->gameRepository = $gameRepository;
    }

    public function getFirstJoinableGame() {
        foreach ($this->gameRepository as $game) {
            if (!$game instanceof PublicGame) {
                continue;
            }

            if ($game->isPossibleToJoin()) {
                return $game;
            }
        }
        return null;
    }

}
