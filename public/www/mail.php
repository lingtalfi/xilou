<?php


use Umail\Umail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// SEND SIMPLE MAIL
//------------------------------------------------------------------------------/
//$res = Umail::create()
//    ->to('lingtalfi@gmail.com')
//    ->from('johndoe@gmail.com')
//    ->subject("Hi, just a test mail")
//    ->htmlBody('Hi, this is <b>just</b> an <span style="color: red">test message</span>')
//    ->plainBody('Hi, this is just an test message')
//    ->send();
//a($res);

//------------------------------------------------------------------------------/
// SEND MAIL IN BATCH MODE OR IN MERGE MODE
//------------------------------------------------------------------------------/
/**
 * In batch mode, each recipient sees only its own mail in the to field,
 * while in merge mode, each recipient sees all the recipients to which the email
 * has been sent. Default is batch.
 */
$res = Umail::create()
    ->to([
        'lingtalfi@gmail.com' => 'ling',
        'agenceweb37@gmail.com' => 'aw37',
//        'delphine@leaderfit.com',
//        'thomas_jefferson@gmail.com',
//        'ally_mac_beal@gmail.com' => 'Ally',
    ])
    ->from('johndoe@gmail.com')
    ->subject("Hi, just a test mail")
    ->htmlBody('Hi, this is <b>just</b> an <span style="color: red">test message</span>')
    ->plainBody('Hi, this is just an test message')
    ->send();
a($res);