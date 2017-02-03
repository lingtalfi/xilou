<?php

use Layout\Layout;


Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/frontOne/frontOne-social.php", "frontOne.access.social"),
])->display();


