<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/logWatcher/logWatcher.php", "logWatcher.access"),
])->display();


