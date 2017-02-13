<?php


use Mail\OrderConfMail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// EMBED A FILE
//------------------------------------------------------------------------------/


OrderConfMail::create()->send([
    'lingtalfi@gmail.com' => 'ling',
//            'zilu@leaderfit.com' => 'zilu',
]);