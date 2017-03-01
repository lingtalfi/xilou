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
use DbTransition\CommandeLigneStatut;
use DbTransition\Fournisseur;
use DbTransition\FournisseurHasArticle;
use DbTransition\TypeContainer;
use QuickPdo\QuickPdo;

//require_once "bigbang.php";
// require_once __DIR__ . '/../public/class-planets/BumbleBee/Autoload/BeeAutoloader.php';
// require_once __DIR__ . '/../public/class-planets/BumbleBee/Autoload/ButineurAutoloader.php';
// ButineurAutoloader::getInst()
//   ->addLocation(__DIR__ . "/../public/class")
//     ->addLocation(__DIR__ . "/../public/class-core")
//     ->addLocation(__DIR__ . "/../public/class-modules")
//     ->addLocation(__DIR__ . "/../public/class-planets");
// ButineurAutoloader::getInst()->start();


//require_once __DIR__ . "/../public/init.php";
//require_once __DIR__ . "/db-init.inc.php";


//QuickPdo::freeExec(file_get_contents(__DIR__ . "/assets/real/zilu.sql"));


$part = 1;


QuickPdo::freeExec(file_get_contents(__DIR__ . "/assets/zilu-structure.sql"));
//QuickPdo::freeExec(file_get_contents(__DIR__ . "/assets/real/article.sql"));
//QuickPdo::freeExec(file_get_contents(__DIR__ . "/assets/real/fournisseur.sql"));
//QuickPdo::freeExec(file_get_contents(__DIR__ . "/assets/real/fournisseur_has_article.sql"));

$d = __DIR__ . "/assets";
QuickPdo::freeQuery(file_get_contents($d . "/csv_fournisseurs_containers.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_fournisseurs_fournisseurs.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_fournisseurs_sav.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_prix_materiel.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_product_details.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_product_list.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_fournisseurs_comparatif.sql"));


Fournisseur::createFournisseurs();
TypeContainer::create();
CommandeLigneStatut::create();

Article::createArticles();
FournisseurHasArticle::createBindures();
require_once __DIR__ . "/crudify.php";

//
//










