<?php


//require_once "/myphp/xilou/public/class/FileCleaner/tools/gen.php";


use FileCleaner\FileCleaner;
use FileCleaner\FileKeeper\OnePerMonthFileKeeper;


require_once __DIR__ . "/../init.php";



FileCleaner::create()
    ->setDir("test")
    ->addKeeper(OnePerMonthFileKeeper::create()->setExtractor(function ($baseName) {
        $year = substr($baseName, 0, 4);
        $month = substr($baseName, 4, 2);
        $day = substr($baseName, 6, 2);

        return "$year-$month-$day";
    }))
    ->clean();