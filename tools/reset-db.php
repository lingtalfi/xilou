<?php


use BullSheet\Generator\LingBullSheetGenerator;
use QuickPdo\QuickPdo;

require_once "bigbang.php";
require_once __DIR__ . "/db-init.inc.php";


$resetDbStructureFile = __DIR__ . "/assets/zilu-structure.sql";

QuickPdo::freeExec(file_get_contents($resetDbStructureFile));
require_once __DIR__ . "/bullsheet-the-db.php";

