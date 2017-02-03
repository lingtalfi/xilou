<?php


use Layout\IfDbLayout;

IfDbLayout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/sqlTools/sqlTools.php", "sqlTools.access"),
])->display();


