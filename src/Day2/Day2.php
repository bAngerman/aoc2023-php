<?php

namespace AOC2023\Day2;

use AOC2023\BaseClass;

class Day2 extends BaseClass
{
    public bool $withPerformance = false;

    public function setupPath() : void
    {
        $this->filePath = __DIR__ . '/input.txt';
    }

    public function run() : void
    {
        $this->part1();
        $this->part2();
    }

    private static int $MAX_RED_CUBES   = 12;
    private static int $MAX_GREEN_CUBES = 13;
    private static int $MAX_BLUE_CUBES  = 14;

    public function part1() : void
    {
        $sum = 0;

        foreach( $this->data as $line ) {
            [$gameString, $games] = explode(":", $line);
            [$_, $gameNumber]     = explode(" ", $gameString);
            $games                = explode(";", $games);
            
            $isValid = true;

            foreach( $games as $gameString ) {

                // Exit early if the game is invalid
                if ( $isValid === false ) break;

                $cubes = explode(",", trim($gameString));
                foreach ( $cubes as $cubeGroup ) {
                    [$quantity, $color] = explode(" ", trim($cubeGroup));

                    // Exit early if the game is invalid
                    if ( $isValid === false ) break;
                    
                    switch ( $color ) {
                        case "red":
                            if ( $quantity > self::$MAX_RED_CUBES ) {
                                $isValid = false;
                            }
                            break;
                        case "green":
                            if ( $quantity > self::$MAX_GREEN_CUBES ) {
                                $isValid = false;
                            }
                            break;
                        case "blue":
                            if ( $quantity > self::$MAX_BLUE_CUBES ) {
                                $isValid = false;
                            }
                            break;
                    }
                }
            }

            if ( $isValid === true ) {
                $sum += (int) $gameNumber;
            }
        }

        $this->part1Answer = $sum;
    }

    public function part2() : void
    {
        $sum = 0;

        foreach( $this->data as $line ) {
            [$gameString, $games] = explode(":", $line);
            $games                = explode(";", $games);

            $cubesPerGame = [
                'red'   => 0,
                'green' => 0,
                'blue'  => 0
            ];

            foreach( $games as $gameString ) {

                $cubes = explode(",", trim($gameString));
                foreach ( $cubes as $cubeGroup ) {
                    [$quantity, $color] = explode(" ", trim($cubeGroup));

                    if ( $cubesPerGame[$color] < $quantity ) {
                        $cubesPerGame[$color] = $quantity;
                    }
                }
            }

            $sum += $cubesPerGame['red'] * $cubesPerGame['green'] * $cubesPerGame['blue'];
        }

        $this->part2Answer = $sum;
    }
}
