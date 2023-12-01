<?php

namespace AOC2023;

class AOCPerformance
{
    private int $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    public function reportPerformance() : void
    {
        dump("Script took " . $this->getScriptTime() . " seconds");
        dump("Script used " . $this->getMemoryUsage());
    }

    private function getScriptTime() : float
    {
        return round(microtime(true) - $this->startTime, 4);
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
