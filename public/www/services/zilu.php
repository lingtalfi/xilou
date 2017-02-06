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
                $value = (int)$_GET['value'];
                if (0 === $value) {
                    $value = null;
                }
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
        case 'apply-fournisseurs':
            if (array_key_exists('type', $_GET) && array_key_exists('commandeId', $_GET)) {
                $type = $_GET['type'];
                $commandeId = $_GET['commandeId'];
                CommandeUtil::applyFournisseurs($commandeId, $type);
                $output = 'ok';
            }
            break;
        case 'container-create':
            if (array_key_exists('type', $_GET) && array_key_exists('name', $_GET)) {
                $type = $_GET['type'];
                $name = $_GET['name'];
                try {

                    $output = ContainerUtil::createContainer($name, $type);
                } catch (\PDOException $e) {
                    if ('23000' === $e->getCode()) {
                        $output = "duplicate";
                    }
                }
            }
            break;
        case 'container-distribute':
            /**
             *  The problem of packing a set of items into a number of bins such that the total weight, volume, etc. does not exceed some maximum value. A simple algorithm (the first-fit algorithm) takes items in the order they come and places them in the first bin in which they fit. In 1973, J. Ullman proved that this algorithm can differ from an optimal packing by as much at 70% (Hoffman 1998, p. 171). An alternative strategy first orders the items from largest to smallest, then places them sequentially in the first bin in which they fit. In 1973, D. Johnson showed that this strategy is never suboptimal by more than 22%, and furthermore that no efficient bin-packing algorithm can be guaranteed to do better than 22% (Hoffman 1998, p. 172).
             */
            // http://www.geeksforgeeks.org/bin-packing-problem-minimize-number-of-used-bins/

            if (array_key_exists('commande_id', $_GET)) {
                $commande_id = (int)$_GET['commande_id'];
                $createContainer = false;
                if (array_key_exists('create-container', $_GET) && 'on' === $_GET['create-container']) {
                    $createContainer = true;
                }

                if (0 === $commande_id) {
                    $output = [
                        'errorType' => "error-commande-empty",
                        'error' => "Veuillez choisir une commande",
                    ];
                }
            }
            break;
        default;
            break;
    }
}


echo json_encode($output);

