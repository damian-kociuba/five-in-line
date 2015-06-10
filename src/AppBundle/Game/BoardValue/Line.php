<?php

namespace AppBundle\Game\BoardValue;

/**
 * Description of Line
 *
 * @author dkociuba
 */
class Line {

    const TWO_SIDE_OPEN = 1;
    const ONE_SIDE_OPEN = 2;
    const HORIZONTAL_DIRECTION = 3;
    const VERTICAL_DIRECTION = 4;
    const ASCENDING_DIRECTION = 5;
    const DESCENDING_DIRECTION = 6;

    public $length;
    public $type;
    public $direction;

}
