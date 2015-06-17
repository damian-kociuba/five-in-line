<?php

namespace AppBundle\Game;
use AppBundle\Game\PlayerBuilder\AIPlayerBuilder;
use AppBundle\Game\PlayerBuilder\HumanPlayerBuilder;
use AppBundle\Game\PlayerBuilder\PlayerBuilderInterface;
/**
 * Description of PlayerBuilder
 *
 * @author dkociuba
 */
class PlayerBuilder {

    const HUMAN_PLAYER = 0;
    const AI_PLAYER = 1;

    private $name;

    public function setPlayerName($name) {
        $this->name = $name;
    }
    
    public function createPlayer($type) {
        $builder = $this->getBuilder($type);
        $builder->setName($this->name);
        $player = $builder->build();
        return $player;
    }
    /**
     * 
     * @param type $type
     * @return PlayerBuilderInterface
     * @throws \Exception
     */
    private function getBuilder($type) {
        switch ($type) {
            case self::HUMAN_PLAYER : return new HumanPlayerBuilder(); 
            case self::AI_PLAYER : return new AIPlayerBuilder(); 
            default: throw new \Exception('Unknow player type');    
        }
    }

}
