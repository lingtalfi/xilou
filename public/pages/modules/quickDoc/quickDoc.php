<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/quickDoc/quickDoc.php", "quickDoc.access"),
])->display();


