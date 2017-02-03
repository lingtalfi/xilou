<?php

use Layout\Layout;


Layout::create()->setElementFiles([
    'body' => Helper::layoutElementIf("modules/frontOne/frontOne-articles.php", "frontOne.access.articles"),
])->display();


