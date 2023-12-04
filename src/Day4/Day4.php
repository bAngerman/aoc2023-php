<?php

namespace AOC2023\Day4;

use AOC2023\BaseClass;

class Day4 extends BaseClass
{
    public bool $withPerformance = false;
    const LINE_REGEX = '/Card *(\d+): ([\d\s]+) \| ([\d\s]+)/';

    public function setupPath() : void
    {
        $this->filePath = __DIR__ . '/input.txt';
    }

    public function run() : void
    {
        $this->part1();
        $this->part2();
    }

    public function part1() : void
    {
        $sum = 0;

        foreach ( $this->data as $line ) {
            preg_match(self::LINE_REGEX, $line, $matches);
            $cardNumber = (int) $matches[1];
            $winning = array_filter(explode(' ', $matches[2]), fn($value) => !empty($value));
            $current = array_filter(explode(' ', $matches[3]), fn($value) =>!empty($value));

            $winningMisses = array_diff($winning, $current);

            // If there are no matches, skip the row (add zero)
            if ( count($winning) === count($winningMisses) ) {
                continue;
            }

            $score = pow(2, (count($winning) - count($winningMisses) - 1));
            $sum += $score;

            // dump(sprintf(
            //     "Card Number: %s\nWinning cards: %s\nCurrent cards: %s\nOverlap: %s\nScore added: %s",
            //     $cardNumber,
            //     implode(', ', $winning),
            //     implode(', ', $current),
            //     implode(', ', array_diff($winning, $winningMisses)),
            //     $score
            // ));
        }

        $this->part1Answer = $sum;
    }

    public function part2() : void
    {
        $copies = [];

        foreach ( $this->data as $idx => $line ) {
            $cardNumber = $idx + 1;
 
            preg_match(self::LINE_REGEX, $line, $matches);
            $winning = array_filter(explode(' ', $matches[2]), fn($value) => !empty($value));
            $current = array_filter(explode(' ', $matches[3]), fn($value) =>!empty($value));

            $winningHits = count($winning) - count(array_diff($winning, $current));
            $addlCopiesFromCopies = array_key_exists($cardNumber, $copies) ? $copies[$cardNumber] : 0;

            if ( $winningHits === 0 ) {
                continue;
            }

            foreach ( range(0, $winningHits - 1) as $offset ) {

                if ( ! isset( $copies[$cardNumber + $offset + 1] ) ) {
                    $copies[$cardNumber + $offset + 1] = 0;
                }

                $copies[$cardNumber + $offset + 1] += (1 + $addlCopiesFromCopies);
            }

            // dump("Card number {$cardNumber} has {$winningHits} matches");
            // dump("Copy count: {$addlCopiesFromCopies}");
            // dump($copies);
        }

        $this->part2Answer = array_sum($copies) + count( $this->data );
    }
}
