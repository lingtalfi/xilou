<?php


use Mailer\AppMailer;
use Mailer\Mailer;

require_once __DIR__ . "/../init.php";


AppMailer::$debug = true;
$nbMsgSend = AppMailer::create()
    ->subject("Your registration on MyWebsite.com")
    ->messageHtml('Hi <b>Marie</b>. Thank you for registering...')
    ->to("marie@bellemere.com")
    ->send();

