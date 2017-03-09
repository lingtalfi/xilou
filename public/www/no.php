<?php


use Fournisseur\FournisseurUtil;
use QuickPdo\QuickPdo;

require_once __DIR__ . "/../init.php";


$ids = [
    132,
    215,
    204,
];

$sIds = implode(', ', $ids);
$items = QuickPdo::fetchAll('select id, commande_id, fournisseur_id from commande_has_article where id in(' . $sIds . ')');
$ret = [];
foreach ($items as $item) {
    a($item);
    if (!array_key_exists($item['commande_id'], $ret)) {
        $ret[$item['commande_id']] = [];
    }
    if (!array_key_exists($item['fournisseur_id'], $ret[$item['commande_id']])) {
        $ret[$item['commande_id']][$item['fournisseur_id']] = [];
    }
    if (!in_array($item['id'], $ret[$item['commande_id']][$item['fournisseur_id']])) {
        $ret[$item['commande_id']][$item['fournisseur_id']][] = $item['id'];
    }
}

echo '<hr>';
a($ret);