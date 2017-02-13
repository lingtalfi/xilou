<?php


use BullSheet\Generator\LingBullSheetGenerator;
use BumbleBee\Autoload\ButineurAutoloader;
use Crud\ResetOption\CrudFilesResetOption;
use Crud\ResetOption\GeneratorsPreferencesResetOption;
use Crud\ResetOption\LeftMenuPreferencesResetOption;
use Crud\Util\CrudFilesGenerator;
use Crud\Util\CrudFilesPreferencesGenerator;
use Crud\Util\LeftMenuPreferencesGenerator;
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


$b = LingBullSheetGenerator::create()->setDir("/myphp/bullsheets-repo/bullsheets");


$nbFournisseurs = 20;
$nbArticles = 100;
$nbContainers = 40;
$nbCommandes = 50;
$nbFournisseurHasArticle = 1200;
$nbCommandeHasArticle = 1800;


$fournisseurIds = [];
$articleIds = [];
$containerIds = [];
$commandeIds = [];
$typeContainerIds = [];


function getRandomId(array $arr, $isNullable = false)
{
    if (true === $isNullable && 0 === rand(0, 1)) {
        return null;
    }
    return rand(0, count($arr) - 1);
}


$typeContainerTable = 'type_container';
if (false !== $id = (QuickPdo::insert($typeContainerTable, [
        'label' => "petit",
        'poids_max' => "10000",
        'volume_max' => "10000",
    ]))
) {
    $typeContainerIds[] = $id;
}
if (false !== $id = (QuickPdo::insert($typeContainerTable, [
        'label' => "moyen",
        'poids_max' => "20000",
        'volume_max' => "20000",
    ]))
) {
    $typeContainerIds[] = $id;
}
if (false !== $id = (QuickPdo::insert($typeContainerTable, [
        'label' => "grand",
        'poids_max' => "40000",
        'volume_max' => "40000",
    ]))
) {
    $typeContainerIds[] = $id;
}


for ($i = 0; $i < $nbFournisseurs; $i++) {
    if (false !== ($id = QuickPdo::insert("fournisseur", [
            'nom' => "F" . sprintf('%02s', $i),
            'email' => $b->email(),
        ]))
    ) {
        $fournisseurIds[] = $id;
    }
}


for ($i = 0; $i < $nbArticles; $i++) {
    if (false !== ($id = QuickPdo::insert("article", [
            'reference_lf' => $b->letters(5),
            'reference_hldp' => $b->letters(5),
            'poids' => $b->float(3, 2),
            'descr_fr' => $b->loremSentence(1, 2),
            'descr_en' => $b->loremSentence(1, 2),
        ]))
    ) {
        $articleIds[] = $id;
    }
}

for ($i = 0; $i < $nbCommandes; $i++) {
    if (false !== ($id = QuickPdo::insert("commande", [
            'reference' => $b->letters(8),
        ]))
    ) {
        $commandeIds[] = $id;
    }
}


for ($i = 1; $i <= $nbContainers; $i++) {
    if (false !== ($id = QuickPdo::insert("container", [
            'nom' => "C" . sprintf('%02s', $i),
            'type_container_id' => getRandomId($typeContainerIds),
        ]))
    ) {
        $containerIds[] = $id;
    }
}


for ($i = 0; $i < $nbFournisseurHasArticle; $i++) {
    QuickPdo::insert("fournisseur_has_article", [
        'fournisseur_id' => getRandomId($fournisseurIds),
        'article_id' => getRandomId($articleIds),
        'reference' => $b->letters(6),
        'prix' => $b->float(3, 2),
        'volume' => $b->float(1, 2),
    ]);
}


for ($i = 0; $i < $nbCommandeHasArticle; $i++) {
    QuickPdo::insert("commande_has_article", [
        'commande_id' => getRandomId($commandeIds),
        'article_id' => getRandomId($articleIds),
        'container_id' => getRandomId($containerIds, true),
        'fournisseur_id' => getRandomId($fournisseurIds, true),
    ]);
}


/**
 * Création du fournisseur leaderfit qui contient FORCEMENT tous les produits
 * (sinon, le design de la db n'est pas bon)
 * (au pire, créer un fournisseur virtuel qui contient tous les produits)
 */


$id = QuickPdo::insert("fournisseur", [
    'nom' => "leaderfit",
    'email' => $b->email(),
]);
foreach ($articleIds as $idArticle) {
    QuickPdo::insert("fournisseur_has_article", [
        'fournisseur_id' => $id,
        'article_id' => $idArticle,
        'reference' => $b->letters(6),
        'prix' => $b->float(3, 2),
        'volume' => $b->float(1, 2),
    ]);
}


//--------------------------------------------
// CRUD GENERATORS
//--------------------------------------------
define('APP_ROOT_DIR', __DIR__ . "/../public");
$options = [];
$options[] = new LeftMenuPreferencesResetOption('crud_leftmenu', 'empty the left menu preferences');
$options[] = new CrudFilesResetOption('crud_files', 'remove the crud files');
$options[] = new GeneratorsPreferencesResetOption('crud_files_prefs', 'empty the crud files preferences');
foreach ($options as $o) {
    $o->reset();
}

CrudFilesPreferencesGenerator::generate();
CrudFilesGenerator::generateCrudFormsFromPreferences();
CrudFilesGenerator::generateCrudListsFromPreferences();
LeftMenuPreferencesGenerator::create()->generate();




