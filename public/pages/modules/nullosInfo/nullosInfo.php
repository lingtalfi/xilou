<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/nullosInfo/nullosInfo.php", "nullosInfo.access"),
])->display();


