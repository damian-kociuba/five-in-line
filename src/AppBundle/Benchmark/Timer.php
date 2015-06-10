<?php

namespace AppBundle\Benchmark;

/**
 * Description of Timer
 *
 * @author dkociuba
 */
class Timer {

    private $startTime;
    private $result = 0;

    public function start() {
        $this->startTime = $this->getMicrotime();
        $this->result = 0;
    }

    public function continueCounting() {
        $this->startTime = $this->getMicrotime();
    }

    public function reset() {
        $this->result = 0;
    }

    public function stop() {
        $this->result += ($this->getMicrotime() - $this->startTime);
    }

    public function getResult() {
        return $this->result;
    }

    private function getMicrotime() {
        list($usec, $sec) = explode(' ', microtime());
        return ((float) $usec + (float) $sec);
    }

}
