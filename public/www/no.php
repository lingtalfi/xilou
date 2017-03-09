<?php


use Fournisseur\FournisseurUtil;
use Mail\OrderProviderConfMail;
use QuickPdo\QuickPdo;
use Updf\AppUpdfUtil;

require_once __DIR__ . "/../init.php";


$lineIds = [
    1,
    2,
    3,
    4,
];
a(OrderProviderConfMail::sendByLineIds($lineIds));