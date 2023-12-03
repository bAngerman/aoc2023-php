<?php

namespace AOC2023;

class AOCPerformance
{
    private float $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    public function reportPerformance() : void
    {
        dump("Script took " . $this->getScriptTime() . " s");
        dump("Script used " . $this->getMemoryUsage());
    }

    private function getScriptTime() : float
    {
        return (microtime(true) - $this->startTime);
    }

    function getMemoryUsage()
    {
        $usage = memory_get_usage(true);
        $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
        return @round($usage/pow(1024,($i=floor(log($usage,1024)))),2).' '.$unit[$i];
    }

    public function end()
    {
        $this->reportPerformance();
    }
}
