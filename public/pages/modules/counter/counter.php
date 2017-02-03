<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/counter/counter.php", "counter.access"),
])->display();


