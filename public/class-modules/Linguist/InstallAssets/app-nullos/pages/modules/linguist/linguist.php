<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/linguist/linguist.php", "linguist.access"),
])->display();


