<?php


use BullSheet\Generator\LingBullSheetGenerator;
use BumbleBee\Autoload\ButineurAutoloader;
use Crud\ResetOption\CrudFilesResetOption;
use Crud\ResetOption\GeneratorsPreferencesResetOption;
use Crud\ResetOption\LeftMenuPreferencesResetOption;
use Crud\Util\CrudFilesGenerator;
use Crud\Util\CrudFilesPreferencesGenerator;
use Crud\Util\LeftMenuPreferencesGenerator;
use DbTransition\Article;
use DbTransition\Fournisseur;
use DbTransition\FournisseurHasArticle;
use QuickPdo\QuickPdo;

//require_once "bigbang.php";
require_once __DIR__ . '/../public/class-planets/BumbleBee/Autoload/BeeAutoloader.php';
require_once __DIR__ . '/../public/class-planets/BumbleBee/Autoload/ButineurAutoloader.php';
ButineurAutoloader::getInst()
    ->addLocation(__DIR__ . "/../public/class")
    ->addLocation(__DIR__ . "/../public/class-core")
    ->addLocation(__DIR__ . "/../public/class-modules")
    ->addLocation(__DIR__ . "/../public/class-planets");
ButineurAutoloader::getInst()->start();


//require_once __DIR__ . "/../public/init.php";
require_once __DIR__ . "/db-init.inc.php";





//require_once __DIR__ . "/create-db-structure.inc.php";
//Fournisseur::createFournisseurs(); // already done
//$fournisseurIds = Fournisseur::getFournisseurIds();
//Article::createArticles();

FournisseurHasArticle::createBindures();





//require_once __DIR__ . "/crudify.php";



