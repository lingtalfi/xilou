<?php


use Bat\FileSystemTool;

require "bigbang.php";


$importFileDir = "/myphp/zuk/public/app/class-planets";
$planetsDir = "/myphp/universe-snapshots/planets";


$files = file($importFileDir . "/_import.txt", \FILE_IGNORE_NEW_LINES|\FILE_SKIP_EMPTY_LINES);

foreach ($files as $dir) {


    $oldFile = $importFileDir . "/$dir";

    if (file_exists($oldFile)) {
        if (is_link($oldFile)) {
            unlink($oldFile);
        } else {
            FileSystemTool::remove($oldFile);
        }
    }


    $planetDir = $planetsDir . "/$dir";
    FileSystemTool::copyDir($planetDir, $oldFile);
}


