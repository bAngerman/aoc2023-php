<?php

namespace AOC2023\Day6;

use AOC2023\BaseClass;

class Day6 extends BaseClass
{
    public bool $withPerformance = true;

    protected array $times     = [];
    protected array $distances = [];

    public function setupPath() : void
    {
        $this->filePath = __DIR__ . '/input.txt';
    }

    public function run() : void
    {
        $this->parseData();
        $this->part1();
        $this->part2();
    }

    protected function parseData(): void
    {
        $this->times = array_values(array_map(
            fn($val): int => (int) $val,
            array_filter(explode(" ", $this->data[0]), fn($val): bool => is_numeric($val))
        ));
        $this->distances = array_values(array_map(
            fn($val): int => (int) $val,
            array_filter(explode(" ", $this->data[1]), fn($val): bool => is_numeric($val))
        ));
    }

    protected function getHoldTimesCount(int $time, int $distance): int
    {
        // The minimum amount of velocity required to reach $distance is
        $holdTimeMin = $time;
        $holdTimeMax = 0;

        for ( $velocity = 0; $velocity < $time; $velocity++ ) {
            $movementTime = $time - $velocity;
            $moveDist = $velocity * $movementTime;
            if ( $moveDist > $distance ) {
                $holdTimeMin = $velocity;
                break;
            }
        }

        // To find the upper hold time limit we can iterate 
        // from the end until we find a valid time.
        for ( $velocity = $time; $velocity > 0; $velocity-- ) {
            $movementTime = $time - $velocity;
            $moveDist = $velocity * $movementTime;
            if ( $moveDist > $distance ) {
                $holdTimeMax = $velocity;
                break;
            }
        }

        return $holdTimeMax - ($holdTimeMin - 1);
        
        // dump("The minimum hold time is: {$holdTimeMin} and the maximum hold time is: {$holdTimeMax}");
        // dump("The number of possible velocities is {$holdTimeOptions}");
    }

    public function part1() : void
    {
        $total = 1;

        foreach ( $this->times as $idx => $time ) {
            $distance = $this->distances[$idx];
            $total = $total * $this->getHoldTimesCount($time, $distance);
        }

        $this->part1Answer = $total;
    }

    public function part2() : void
    {
        $time = implode('', $this->times);
        $distance = implode('', $this->distances);

        $this->part2Answer = $this->getHoldTimesCount($time, $distance);
    }
}
