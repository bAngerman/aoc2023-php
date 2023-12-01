<?php

namespace AOC2023;

class BaseClass
{
    public $part1Answer;
    public $part2Answer;
    public array $data = [];
    public string $filePath;

    private null|AOCPerformance $performance = null;
    public bool $withPerformance = false;

    public function __construct()
    {
        $this->setupPath();
        $this->setup();

        if ( $this->withPerformance ) {
            $this->performance = (new AOCPerformance);
        }
    }
    
    public function __destruct() {
        dump( "Part 1 Answer:", $this->part1Answer );
        dump( "Part 2 Answer:", $this->part2Answer );

        if ( $this->performance ) {
            $this->performance->end();
        } 
    }

    public function setupPath() : void
    {
        // 
    }

    public function setup() : void
    {
        $f = file_get_contents($this->filePath);

        $f = explode("\r\n", $f);        

        $this->data = $f;

        return;
    }
}
