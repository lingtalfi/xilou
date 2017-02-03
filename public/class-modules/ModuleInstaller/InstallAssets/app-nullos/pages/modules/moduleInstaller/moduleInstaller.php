<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/moduleInstaller/moduleInstaller.php", "moduleInstaller.access"),
])->display();


