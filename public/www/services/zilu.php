<?php


use Commande\CommandeUtil;
use Container\ContainerUtil;
use Fournisseur\FournisseurUtil;
use QuickPdo\QuickPdo;

require_once __DIR__ . "/../../init.php";


function unric($ricValue)
{
    $sep = '--*--';
    return explode($sep, $ricValue);
}


$output = '';
if (array_key_exists('action', $_GET)) {
    $action = $_GET['action'];
    switch ($action) {
        case 'commande-container-selector':
            $output = ContainerUtil::getId2Labels();
            break;
        case 'commande-fournisseur-selector':
            if (array_key_exists('article_id', $_GET)) {
                $output = FournisseurUtil::getComparisonInfo((int)$_GET['article_id']);
            }
            break;
        case 'commande-change-container':
            if (array_key_exists('value', $_GET) && array_key_exists('ric', $_GET)) {
                $value = $_GET['value'];
                $ric = $_GET['ric'];
                list($commandeId, $articleId) = unric($ric);
                $commandeId = (int)$commandeId;
                $articleId = (int)$articleId;
                QuickPdo::update('commande_has_article', [
                    'container_id' => $value,
                ], [
                    ['commande_id', '=', $commandeId],
                    ['article_id', '=', $articleId],
                ]);
                $output = "ok";
            }
            break;
        case 'commande-change-fournisseur':
            if (array_key_exists('value', $_GET) && array_key_exists('ric', $_GET)) {
                $value = $_GET['value'];
                $ric = $_GET['ric'];
                list($commandeId, $articleId) = unric($ric);
                $commandeId = (int)$commandeId;
                $articleId = (int)$articleId;
                QuickPdo::update('commande_has_article', [
                    'fournisseur_id' => $value,
                ], [
                    ['commande_id', '=', $commandeId],
                    ['article_id', '=', $articleId],
                ]);
                $output = "ok";
            }
            break;
        case 'article-autocomplete':
            if (array_key_exists('term', $_GET)) {
                $term = $_GET['term'];
                if (false !== ($res = QuickPdo::fetchAll('select 
              concat(h.fournisseur_id, "-", h.article_id) as id,
              concat(h.reference, " : ", f.nom, " (", h.prix, "€)") as label,
              h.reference as value
              from fournisseur_has_article h 
              inner join fournisseur f on f.id=h.fournisseur_id
              where h.reference like :ref
              ', [
                        'ref' => '%' . str_replace('%', '\%', $term) . '%',
                    ]))
                ) {
                    $output = $res;
                }
            }
            break;
        default;
            break;
    }
}


echo json_encode($output);

