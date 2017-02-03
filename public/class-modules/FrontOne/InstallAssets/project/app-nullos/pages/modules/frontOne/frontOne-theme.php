<?php

use Layout\Layout;


Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/frontOne/frontOne-theme.php", "frontOne.access.theme"),
])->display();


