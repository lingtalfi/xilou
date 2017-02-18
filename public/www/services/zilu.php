<?php


use Commande\CommandeUtil;
use Container\ContainerUtil;
use CsvImport\CommandeImporterUtil;
use Fournisseur\FournisseurUtil;
use Mail\OrderConfMail;
use Mail\OrderProviderConfMail;
use QuickPdo\QuickPdo;
use Sav\SavAjaxFormInsert;
use Sav\SavDetailsArrayRenderer;
use Sav\SavUtil;

require_once __DIR__ . "/../../init.php";


function unric($ricValue)
{
    $sep = '--*--';
    return explode($sep, $ricValue);
}


$output = '';
$isHtml = false;
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
        case 'sav-details':
            if (array_key_exists('savId', $_GET)) {
                $savId = $_GET['savId'];
                $isHtml = true;
                ob_start();
                SavDetailsArrayRenderer::create()->prepareBySavId($savId)->render();
                $output = ob_get_clean();
            }
            break;
        case 'sav-transform-form':
            if (array_key_exists('ric', $_GET)) {
                $isHtml = true;
                $ric = $_GET['ric'];
                SavAjaxFormInsert::printForm($ric);
            }
            break;
        case 'sav-transform-insert':
            if (array_key_exists('ric', $_GET)) {
                $ric = $_GET['ric'];
                list($commandeId, $articleId) = unric($ric);
                $data = $_GET;
                if (true === SavUtil::addByCommandLine($commandeId, $articleId, $data)) {
                    $output = 'ok';
                } else {
                    $output = 'ko';
                }
            }
            break;
        case 'update-commande-field':
            if (
                array_key_exists('col', $_GET) &&
                array_key_exists('ric', $_GET) &&
                array_key_exists('value', $_GET)
            ) {
                $col = $_GET['col'];
                $ric = $_GET['ric'];
                $value = $_GET['value'];

                list($commandeId, $articleId) = unric($ric);

                $res = QuickPdo::update('commande_has_article', [
                    $col => $value,
                ], [
                    ['commande_id', '=', $commandeId],
                    ['article_id', '=', $articleId],
                ]);


                if (false !== $res) {
                    $output = 'ok';
                } else {
                    $output = 'ko';
                }
            }
            break;
        case 'send-mail-purchase-order':

            if (array_key_exists("commande_id", $_GET) &&
                array_key_exists('estimated_date', $_GET)
            ) {
                $commandeId = $_GET['commande_id'];
                $estimatedDate = $_GET['estimated_date'];

                $mail = MAIL_DIDIER;
                if (array_key_exists('test', $_GET)) {
                    $mail = MAIL_ZILU;
                }
                $n = OrderConfMail::sendByCommandeId($mail, $commandeId, $estimatedDate);
                if (1 === $n) {
                    $output = 'ok';
                } else {
                    $output = "Une erreur est survenue, le mail n'a pas été envoyé; veuillez contacter le webmaster";
                }
            }
            break;
        case 'send-mail-pro-purchase-order':
            if (
                array_key_exists("commande_id", $_GET) &&
                array_key_exists("provider_id", $_GET) &&
                array_key_exists('signature', $_GET)
            ) {
                $commandeId = $_GET['commande_id'];
                $providerId = $_GET['provider_id'];
                $signature = $_GET['signature'];

                $mail = MAIL_DIDIER;
                if (array_key_exists('test', $_GET)) {
                    $mail = MAIL_ZILU;
                }

                try {
                    $n = OrderProviderConfMail::sendByCommandeIdFournisseurId($mail, $commandeId, $providerId, $signature);
                    if (1 === $n) {
                        $output = [
                            'success' => 'ok',
                        ];
                    } else {
                        $output = [
                            "error" => "Une erreur est survenue, le mail n'a pas été envoyé; veuillez contacter le webmaster",
                        ];
                    }
                } catch (\Exception $e) {
                    $output = [
                        'error' => $e->getMessage(),
                    ];
                }
            }
            break;
        case 'csv-import-form':
            if (array_key_exists('csvfile', $_FILES) && array_key_exists('nom', $_POST)) {
                $tmp_name = $_FILES['csvfile']['tmp_name'];
                $cmdName = $_POST['nom'];
                $cmdFileBaseName = preg_replace('[^a-zA-Z0-9_.-]', '', $cmdName);
                $cmdFileName = $cmdFileBaseName . '.xlsx';
                $csvFile = APP_COMMANDE_IMPORTS_DIR . "/" . $cmdFileName;

                if (move_uploaded_file($tmp_name, $csvFile)) {
                    try {
                        $missingRefs = [];
                        if (false !== ($idCommande = CommandeImporterUtil::createCommandeByCsvFile($csvFile, $cmdName, $missingRefs))) {
                            if (count($missingRefs) > 0) {
                                $output = [
                                    'missingRefs' => "La commande a bien été importée; les références suivantes étaient manquantes et ont été rajoutées: " . implode(',', $missingRefs) . "
                                    Veuillez rafrîchir la page pour continuer.",
                                ];
                            } else {
                                $output = [
                                    'success' => $idCommande,
                                ];
                            }
                        } else {
                            $output = [
                                'error' => "Un problème est survenu, veuillez contacter le webmaster",
                            ];
                        }
                    } catch (\Exception $e) {
                        if ('23000' === $e->getCode()) {
                            $output = [
                                'error' => "Ce nom de commande existe déjà",
                            ];
                        } else {
                            $output = [
                                'error' => $e->getMessage(),
                            ];
                        }
                    }
                } else {
                    $output = [
                        "error" => "Veuillez charger un fichier csv",
                    ];
                }
            }
            break;
        default:
            break;
    }
}


if (false === $isHtml) {
    echo json_encode($output);
} else {
    echo $output;
}

