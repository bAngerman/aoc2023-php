<?php

namespace AOC2023\Day7;

use AOC2023\BaseClass;

class Day7 extends BaseClass
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

    protected $faceCardScores = [
        'T' => 10,
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];

    protected function scoreHand(string $hand): int
    {
        $cards = str_split($hand);
        $cardCounts = [];

        foreach ( $cards as $c ) {
            if ( ! isset( $cardCounts[$c] ) ) {
                $cardCounts[$c] = 0;
            }
            $cardCounts[$c]++;
        }

        $cardTypes = array_keys( $cardCounts );

        // Five of a kind
        if ( count( $cardCounts ) === 1 ) {
            return 1;
        }
        
        // Either four of a kind, or full house
        if ( count( $cardCounts ) === 2 ) {
            if ( $cardTypes[0] === 4 || $cardTypes[1] === 4 ) {
                return 2;
            }

            // Must be full house.
            return 3;
        }

        // Either three of a kind, or 2 pair.
        if ( count( $cardCounts ) === 3 ) {
            if ( $cardTypes[0] === 3 || $cardTypes[1] === 3 || $cardTypes[2] === 3 ) {
                return 4;
            }

            // Must be 2 pair.
            return 5;
        }

        // One pair.
        if ( isset(array_flip($cardCounts)[2]) ) {
            return 6;
        }

        return 7;
    }

    public function part1() : void
    {
        $sum = 0;
        $scores = [];

        foreach ( $this->data as $line ) {
            [$hand, $bid] = explode(' ', $line);

            $scores[] = [
                'hand'  => $hand,
                'bid'   => (int) $bid,
                'score' => $this->scoreHand($hand),
            ];
        }

        usort($scores, function ($a, $b) {
            if ( $a['score'] < $b['score'] ) {
                return 1;
            }

            if ( $a['score'] > $b['score'] ) {
                return -1;
            }

            for ( $i = 0; $i < strlen($a['hand']); $i++ ) {
                $aCard = $a['hand'][$i];
                if ( isset( $this->faceCardScores[$aCard] ) ) {
                    $aCard = $this->faceCardScores[$aCard];
                } else {
                    $aCard = (int) $aCard;
                }

                $bCard = $b['hand'][$i];
                if ( isset( $this->faceCardScores[$bCard] ) ) {
                    $bCard = $this->faceCardScores[$bCard];
                } else {
                    $bCard = (int) $bCard;
                }

                if ( $aCard > $bCard ) {
                    return 1;
                }

                if ( $aCard < $bCard ) {
                    return -1;
                }
            }
        });

        // dd( $scores[0], $scores[1], $scores[2], $scores[3], $scores[4] );

        foreach ( $scores as $idx => $score ) {
            $sum += $score['bid'] * ($idx + 1);
        }

        $this->part1Answer = $sum;
    }

    public function part2() : void
    {
        
    }
}
