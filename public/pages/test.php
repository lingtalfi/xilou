<?php
use DirTransformer\Scanner\Scanner;
use DirTransformer\Transformer\TrackingMapRegexTransformer;

require "bigbang.php";


$srcDir = "/pathto/php/projects/nullos-admin/doc";
$dstDir = "/pathto/php/projects/nullos-admin/doc2";


$t = TrackingMapRegexTransformer::create()
    ->regex('!<-(.*)->!')
    ->map([
        'another link' => 'http://mydoc.com/another-link.md',
    ])
    ->onFound(function ($match, $value) {
        return '[' . $match . '](' . $value . ')';
    });
Scanner::create()
    ->allowedExtensions(['md'])
    ->dryRun()
    ->limit(10)
    ->addTransformer($t)
    ->copy($srcDir, $dstDir);


a($t->getFoundList());
a($t->getUnfoundList());