<?php

require __DIR__  . '/../vendor/autoload.php';

/**
 * Usage:
 * 
 * php src/index.php {day_number}
 * eg. php src/index.php -d 2
 * will run Day2 class
 */

$short_opts = "d:";
$long_opts = [];

$options = getopt($short_opts, $long_opts);

$day = isset( $options["d"] ) ? (int) $options["d"] : 0;
$perf = isset( $options["performance"] );

if ( $day === 0 ) {
    throw new \ParseError("Pass an integer for the day to the php cli command.\neg \"php src/index.php -d 1\" for day 1\n");
}

$class = sprintf("AOC2023\Day%s\Day%s", $day, $day);

if ( ! class_exists( $class) ) {
    throw new \ParseError("Class not found: " . $class);
}

(new $class($perf))->run();
