<?php

namespace AOC2023;

require __DIR__  . '/../vendor/autoload.php';

use Composer\Script\Event;

class AOCHelper
{
    public static function day(Event $event) : void
    {
        if ( empty( $event->getArguments() )) {
            throw new \ErrorException("Provide a valid day to scaffold");
        }
        
        $day =  (int) $event->getArguments()[0];
        
        if ( $day === 0 ) {
            throw new \ErrorException("Provide a valid day to scaffold");
        }

        $new_day = sprintf("Day%s", $day);

        if ( file_exists(__DIR__ . "/" . $new_day) ) {
            throw new \ErrorException("Folder already exists. Delete the folder to scaffold this day.");
        }

        $src_dir = sprintf("%s/%s", __DIR__, "_day-template");
        $dest_dir = sprintf("%s/%s", __DIR__, $new_day);

        // Create new day folder.
        if ( ! file_exists($dest_dir) ) {
            mkdir($dest_dir);
        }

        // Copy files.
        foreach( scandir($src_dir) as $file) {
            if ( $file === "." || $file === ".." ) {
                continue;
            }

            $filename = $file;
            if ( $filename === "DayX.php" ) {
                $filename = $new_day . ".php";
            }

            copy($src_dir . "/". $file, $dest_dir . "/" . $filename);
        }

        $day_class_file = $dest_dir . "/" . $new_day . ".php";

        // Modify DayX.php file contents to be accurate to new day.
        file_put_contents(
            $day_class_file,
            str_replace(
                'DayX',
                $new_day,
                file_get_contents($day_class_file)
            )
        );
    }
}
