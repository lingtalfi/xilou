<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/iconsViewer/iconsViewer.php", "iconsViewer.access"),
])->display();


