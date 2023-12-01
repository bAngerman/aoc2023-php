<?php

namespace AOC2023\Day1;

use AOC2023\BaseClass;

class Day1 extends BaseClass
{
    public bool $withPerformance = true;

    public function setupPath() : void
    {
        $this->filePath = __DIR__ . '/input.txt';
    }

    public function run() : void
    {
        $this->part1();
        $this->part2();
    }

    private static array $numMap = [
        3 => [
            "one"   => 1,
            "two"   => 2,
            "six"   => 6,
        ],
        4 => [
            "four"  => 4,
            "five"  => 5,
            "nine"  => 9,
        ],
        5 => [
            "three" => 3,
            "seven" => 7,
            "eight" => 8,
        ]
    ];

    public function part1() : void
    {
        $sum = 0;

        foreach ( $this->data as $line ){
            if ( empty( $line) ) continue;

            $chars = str_split($line);

            $left = 0;
            $right = 0;
            $idx = 0;

            while ( $idx < count($chars) ) {
                if ( is_numeric($chars[$idx]) ) {
                    $left = $chars[$idx];
                    // dump("Left value is $left");
                    break;
                }
                $idx++;
            }

            $idx = count($chars) - 1;
            while ( $idx > -1 ) {
                if ( is_numeric($chars[$idx]) ) {
                    $right = $chars[$idx];
                    // dump("Right value is $right");
                    break;
                }
                $idx--;
            }

            $num = (int) ($left . $right);
            
            // dump("Final num is $num");
            // dump("\n");

            $sum += $num;
        }

        $this->part1Answer = $sum;
    }

    public function part2() : void
    {
        $sum = 0;

        foreach ( $this->data as $line ){
            if ( empty( $line) ) continue;
            
            $chars = str_split($line);
            $charsCount = count($chars);

            $left = null;
            $right = null;
            $idx = 0;

            while( $idx < $charsCount ) {
                $rem = $charsCount - $idx;

                if ( is_numeric($chars[$idx]) ) {
                    $left = $chars[$idx];
                    // dump("Left value is $left");
                    break;
                }

                if ( $left === null && $rem > 2 ) {
                    $left = $this->checkForNumString(3, substr($line, $idx, 3));
                }

                if ( $left === null && $rem > 3 ) {
                    $left = $this->checkForNumString(4, substr($line, $idx, 4));
                }

                if ( $left === null && $rem > 4 ) {
                    $left = $this->checkForNumString(5, substr($line, $idx, 5));
                }

                if ( $left !== null ) {
                    // dump("Left value is $left");
                    break;
                }

                $idx++;
            }

            $idx = $charsCount - 1;
            while ( $idx > -1 ) {
                $rem = $charsCount - ($idx - $charsCount - 1);

                if ( is_numeric($chars[$idx]) ) {
                    $right = $chars[$idx];
                    break;
                }

                if ( $right === null && $rem > 2 ) {
                    $right = $this->checkForNumString(3, substr($line, $idx - 2, 3));
                    // dump( substr($line, $idx - 2, 3) );
                }

                if ( $right === null && $rem > 3 ) {
                    $right = $this->checkForNumString(4, substr($line, $idx - 3, 4));
                    // dump( substr($line, $idx - 3, 4) );
                }

                if ( $right === null && $rem > 4 ) {
                    $right = $this->checkForNumString(5, substr($line, $idx - 4, 5));
                    // dump( substr($line, $idx - 4, 5) );
                }

                if ( $right !== null ) {
                    // dump("Right value is $right\n");
                    break;
                }

                $idx--;
            }

            $num = (int) ($left . $right);

            // dump("Final num is $num");
            // dump("\n");

            $sum += $num;
        }

        $this->part2Answer = $sum;
    }

    private function checkForNumString(int $numCharCount, string $subString): ?string
    {
        $nums = self::$numMap[$numCharCount];

        if ( in_array($subString, array_keys($nums) ) ) {
            return $nums[$subString];
        }

        return null;
    }
}
