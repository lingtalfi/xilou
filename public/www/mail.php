<?php


use Umail\Umail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// USING VARIABLES WITHIN THE BODY
//------------------------------------------------------------------------------/
/**
 * In batch mode, each recipient sees only its own mail in the to field,
 * while in merge mode, each recipient sees all the recipients to which the email
 * has been sent. Default is batch.
 */
$res = Umail::create()
    ->to([
        'lingtalfi@gmail.com' => 'ling',
    ])
    ->from('johndoe@gmail.com')
    ->subject("Hi {somebody}, just a test mail")
    ->setVars([
        'message' => 'variable message',
    ], function ($email) {
        return [
            'somebody' => substr($email, 0, strpos($email, '@')),
        ];
    })
    ->htmlBody('Hi, this is <b>just</b> a <span style="color: red">{message}</span>')
    ->plainBody('Hi, this is just a test message')
    ->send();
a($res);