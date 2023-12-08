<?php

namespace AOC2023\Day8;

use AOC2023\BaseClass;

class Day8 extends BaseClass
{
    public bool $withPerformance = false;

    public function setupPath() : void
    {
        $this->filePath = __DIR__ . '/input.txt';
    }

    protected array $instructions = [];
    protected array $map = [];

    protected function parseData()
    {
        $this->instructions = str_split(array_shift($this->data));
        array_shift($this->data);

        foreach ( $this->data as $line ) {
            preg_match('/(.*?) = \((.*?)\)/', $line, $matches);
            list($left, $right) = explode(', ', $matches[2]);
            $this->map[$matches[1]] = [
                $left,
                $right,
            ];
        }
    }

    public function run() : void
    {
        $this->parseData();
        // $this->part1();
        $this->part2();
    }

    public function part1() : void
    {
        $pos = 'AAA';
        $steps = 0;

        // dump( $this->instructions, $this->map );

        while ( $pos !== 'ZZZ' ) {
            $instruction = $this->instructions[$steps % count($this->instructions)];

            if ( $instruction === "L" ) {
                $pos = $this->map[$pos][0];
            } else {
                $pos = $this->map[$pos][1];
            }
            $steps++;

            // dump("Instruction is {$instruction}. Moved to {$pos}");
        }

        $this->part1Answer = $steps;
    }

    public function part2() : void
    {
        $positions = array_values(
            array_filter(
                array_keys($this->map),
                fn(string $pos): bool => $pos[2] === "A"
            )
        );
        $steps = 0;
        $lcmBecauseBruteForceDidntWork = [];

        while ( count($positions) > 0 ) {
            $instruction = $this->instructions[$steps % count($this->instructions)];

            $moveIdx = $instruction === "L" ? 0 : 1; 
            $steps++;

            // dump($steps, $instruction, $positions);

            foreach ( $positions as $idx => $pos ) {
                $positions[$idx] = $this->map[$pos][$moveIdx];

                // If this ends with Z we are done this particular position.
                if ( $positions[$idx][2] === "Z" ) {
                    $lcmBecauseBruteForceDidntWork[] = $steps;
                    unset($positions[$idx]);
                }
            }
        }

        $this->part2Answer = $lcmBecauseBruteForceDidntWork;
    }
}
