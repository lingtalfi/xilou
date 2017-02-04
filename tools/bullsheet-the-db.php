<?php


use BullSheet\Generator\LingBullSheetGenerator;
use QuickPdo\QuickPdo;

require_once "bigbang.php";
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


function getRandomId(array $arr, $isNullable = false)
{
    if (true === $isNullable && 0 === rand(0, 1)) {
        return null;
    }
    return rand(0, count($arr) - 1);
}


for ($i = 0; $i < $nbFournisseurs; $i++) {
    if (false !== ($id = QuickPdo::insert("fournisseur", [
            'nom' => "F" . sprintf('%02s', $i),
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

for ($i = 0; $i < $nbContainers; $i++) {
    if (false !== ($id = QuickPdo::insert("container", [
            'nom' => "C" . sprintf('%02s', $i),
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
]);
foreach ($articleIds as $idArticle) {
    QuickPdo::insert("fournisseur_has_article", [
        'fournisseur_id' => $id,
        'article_id' => $idArticle,
        'reference' => $b->letters(6),
        'prix' => $b->float(3, 2),
    ]);
}





