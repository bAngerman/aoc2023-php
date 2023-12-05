<?php

namespace AOC2023\Day5;

use AOC2023\BaseClass;

class Day5 extends BaseClass
{
    public bool $withPerformance = false;

    protected array $seeds     = [];
    protected array $seedPairs = [];
    protected array $maps      = [];

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

    private function parseData(): void
    {
        preg_match('/\bseeds:\s*([\d\s]+)\b/', $this->data[0], $seedMatches);
        $this->seeds = explode(' ', $seedMatches[1]);
        
        $mapKey = '';
        foreach ( $this->data as $idx => $line ) {
            if ( $line === '' ) continue;
            if ( $idx === 0 ) continue;

            if ( strpos($line, 'map') !== false ) {
                $mapKey = str_replace(' map:', '', $line);
                continue;
            }

            if ( ! isset( $this->maps[ $mapKey ] ) ) {
                $this->maps[ $mapKey ] = [];
            }

            $this->maps[ $mapKey ][] = explode(' ', $line);
        }
        
        return;
    }

    protected function findSeedValue(array $map, int $source): int
    {
        foreach ( $map as $mappings ) {
            [$destinationStart, $sourceStart, $range] = $mappings;
            $destinationStart = (int) $destinationStart;
            $sourceStart      = (int) $sourceStart;
            $range            = (int) $range;

            // If the source start is greater than the source then it cannot map.
            if ( $sourceStart > $source ) {
                continue;
            }

            // If this condition is true then the source can be mapped to a destination.
            if (($sourceStart + $range) >= $source ) {
                return $source + ($destinationStart - $sourceStart);
            }
        }

        // Not mapped, so return the source value.
        return $source;
    }

    public function part1() : void
    {
        $locations = [];

        foreach( $this->seeds as $seed ) {
            $mappedVal = (int) $seed;

            foreach ( $this->maps as $mapKey => $map ) {
                $mappedVal = $this->findSeedValue($map, $mappedVal);
            }

            $locations[] = $mappedVal;
        }

        $this->part1Answer = min($locations);
    }

    public function part2() : void
    {
        $this->seedPairs = array_chunk($this->seeds, 2);

        $seeds     = [];
        $newSeeds  = [];

        foreach ( $this->seedPairs as $pair ) {
            $seedStart = (int) $pair[0];
            $seedRange = (int) $pair[1];
            $seeds[] = [$seedStart, $seedStart + $seedRange];
        }

        while( count($seeds) ) {
            [$seedStart, $seedEnd] = array_pop($seeds);

            $newSeeds[] = [$seedStart, $seedEnd];

            foreach ( $this->maps as $map ) {
                foreach ( $map as $mappings ) {
                    [$destinationStart, $sourceStart, $range] = $mappings;
                    $destinationStart = (int) $destinationStart;
                    $sourceStart      = (int) $sourceStart;
                    $range            = (int) $range;

                    $overlapStart = max($seedStart, $sourceStart);
                    $overlapEnd = min($seedEnd, $sourceStart + $range);

                    if ( $overlapStart < $overlapEnd ) {
                        $newSeeds[] = [$overlapStart - $sourceStart + $destinationStart, $overlapEnd - $sourceStart + $destinationStart];

                        if ( $overlapStart > $seedStart ) {
                            $seeds[] = [$seedStart, $overlapStart];
                        }
                        
                        if ( $seedEnd > $overlapEnd ) {
                            $seeds[] = [$overlapEnd, $seedEnd];
                        }

                        break 2;
                    }
                }
            }
        }

        $seeds = $newSeeds;

        $this->part2Answer = min($seeds[0]);
    }
}
