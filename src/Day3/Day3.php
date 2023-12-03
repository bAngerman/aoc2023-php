<?php

namespace AOC2023\Day3;

use AOC2023\BaseClass;

class Day3 extends BaseClass
{
    public bool $withPerformance = false;

    public function setupPath() : void
    {
        $this->filePath = __DIR__ . '/input.txt';
    }

    public function run() : void
    {
        $this->data = array_map(function(string $line) {
            return str_split($line);
        }, $this->data);

        $this->part1();
        $this->part2();
    }

    
    private function isEnginePartValid(int $colStart, int $colEnd, int $row): bool
    {
        // If there is a symbol within the column range of $colStart - 1 to $colEnd + 1
        // And within the row range of $row - 1 to $row + 1 then this is an engine part.

        $colStart = $colStart - 1;
        $colEnd   = $colEnd + 1;
        $rowStart = $row - 1;
        $rowEnd   = $row + 1;

        $rowIdx = $rowStart;

        while ( $rowIdx <= $rowEnd ) {
            $colIdx = $colStart;

            // Check for oob row.
            if ( ! isset( $this->data[$rowIdx] ) ) {
                $rowIdx++;
                continue;
            }

            while ( $colIdx <= $colEnd ) {
                // First check for oob column.
                if ( ! isset( $this->data[$rowIdx][$colIdx] ) ) {
                    $colIdx++;
                    continue;
                }

                $val = $this->data[$rowIdx][$colIdx];

                // If this is a number, or it's a period skip it.
                if ( is_numeric($val) || $val === '.' ) {
                    $colIdx++;
                    continue;
                }

                // If we reached this code then a symbol was found adjacent to an engine part number
                return true;
            }

            $rowIdx++;
        }

        // Not an engine part.
        return false;
    }

    public function part1() : void
    {
        $sum = 0;
        for ( $rowIdx = 0; $rowIdx < count($this->data); $rowIdx++) {
            for ( $colIdx = 0; $colIdx < count($this->data[$rowIdx]); $colIdx++) {
                $colVal = $this->data[$rowIdx][$colIdx];

                // Skip symbols.
                if ( ! is_numeric($colVal) ) {
                    continue;
                }

                // Get entire number value by moving to the right until a non-numeric value is found.
                $enginePartValue = $colVal;
                $enginePartIdx = 1;

                while ( isset($this->data[$rowIdx][$colIdx + $enginePartIdx]) 
                    && is_numeric($this->data[$rowIdx][$colIdx + $enginePartIdx]) ) {
                    $enginePartValue .= $this->data[$rowIdx][$colIdx + $enginePartIdx];
                    $enginePartIdx++;
                }

                // Check surroundings to see if the engine part should be considered.
                if ( $this->isEnginePartValid($colIdx, ($colIdx + $enginePartIdx - 1), $rowIdx) ) {
                    // dump($enginePartValue . ' is a part number');
                    $sum += (int) $enginePartValue;
                }

                $colIdx += $enginePartIdx;
                if ( $colIdx > count($this->data[$rowIdx]) ) {
                    break;
                }
            }
        }

        $this->part1Answer = $sum;
    }

    public function part2() : void
    {
        $sum = 0;
        for ( $rowIdx = 0; $rowIdx < count($this->data); $rowIdx++) {
            for ( $colIdx = 0; $colIdx < count($this->data[$rowIdx]); $colIdx++) {
                $colVal = $this->data[$rowIdx][$colIdx];

                if ( $colVal !== "*") {
                    continue;
                }

                $gearVal1 = null;
                $gearVal2 = null;

                // dump('Checking gear at ' . $rowIdx . ', ' . $colIdx);

                // If the gear has two engine parts near it then mulitply strings and add to sum.
                for ( $gearRow = $rowIdx - 1; $gearRow <= $rowIdx + 1; $gearRow++ ) {
                    if ( ! isset( $this->data[$gearRow] ) ) continue;

                    for ( $gearCol = $colIdx - 1; $gearCol <= $colIdx + 1; $gearCol++ ) {
                        // dump("Gear check at: " . $gearRow . ", " . $gearCol );
                        // Skip if out of range.
                        if ( ! isset( $this->data[$gearRow][$gearCol] ) ) continue;
                        
                        // Skip if non-numeric.
                        if ( ! is_numeric( $this->data[$gearRow][$gearCol] ) ) continue;

                        

                        if ( $gearVal1 == null ) {
                            $enginePartIdx = $gearCol;
                            // Find the start index of the engine part string.
                            while ( isset($this->data[$gearRow][$enginePartIdx - 1])
                                && is_numeric($this->data[$gearRow][$enginePartIdx - 1]) ) {
                                $enginePartIdx -= 1;
                            }

                            // Now, build engine part string.
                            while( isset($this->data[$gearRow][$enginePartIdx])
                                && is_numeric($this->data[$gearRow][$enginePartIdx]) ) {         
                                // dump( "Concat value " . $this->data[$gearRow][$enginePartIdx] . " onto gear 1");                   
                                $gearVal1 .= $this->data[$gearRow][$enginePartIdx];
                                // dump('Gear val 1: ' . $gearVal1);
                                $enginePartIdx += 1;
                            }

                            // Move the column to the end of the string + 1 position in case there is another engine part near.
                            $gearCol = $enginePartIdx;
                            continue;
                        }

                        if ( $gearVal2 == null ) {
                            $enginePartIdx = $gearCol;
                            // Find the start index of the engine part string.
                            while ( isset($this->data[$gearRow][$enginePartIdx - 1])
                                && is_numeric($this->data[$gearRow][$enginePartIdx - 1]) ) {
                                $enginePartIdx -= 1;
                            }

                            // Now, build engine part string.
                            while( isset($this->data[$gearRow][$enginePartIdx])
                                && is_numeric($this->data[$gearRow][$enginePartIdx]) ) {         
                                // dump( "Concat value " . $this->data[$gearRow][$enginePartIdx] . " onto gear 2");                   
                                $gearVal2 .= $this->data[$gearRow][$enginePartIdx];
                                // dump('Gear val 2: ' . $gearVal2);
                                $enginePartIdx += 1;
                            }

                            // Move the column to the end of the string + 1 position in case there is another engine part near.
                            $gearCol = $enginePartIdx;
                        }

                        // If we have populated both gearVal1 and gearVal2 then we have a valid pair around the gear.
                        // And we can add to the sum and break out of this check.
                        if ( $gearVal1 != null && $gearVal2 != null ) {
                            $sum += ((int) $gearVal1 * (int) $gearVal2);
                            break 2;
                        }
                    }
                }

                // dump( $gearVal1, $gearVal2, $sum );
            }
        }

        $this->part2Answer = $sum;
    }
}
