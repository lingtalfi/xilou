<?php


use Umail\Umail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// ATTACH A FILE
//------------------------------------------------------------------------------/
$file = "/Users/lafitte/Desktop/zilu-db.png";
$res = Umail::create()
    ->to([
        'lingtalfi@gmail.com' => 'ling',
    ])
    ->from('johndoe@gmail.com')
    ->subject("Hi, testing attach file")
    ->htmlBody('Hi, this is <b>just</b> a <span style="color: red">message to test file attachment</span>')
    ->plainBody('Hi, this is just a test message to test file attachment')
    ->attachFile($file)
    ->send();
a($res);