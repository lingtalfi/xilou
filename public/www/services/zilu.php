<?php


use Backup\AppBackup;
use Bin\BinGuiUtil;
use Bin\CommandeToBinHelper;
use Bin\Exception\WeightOverloadException;
use Commande\CommandeUtil;
use CommandeHasArticle\CommandeHasArticleUtil;
use CommandeLigneStatut\CommandeLigneStatutUtil;
use Container\ContainerUtil;
use CsvExport\CommandeExporterUtil;
use CsvImport\CommandeImporterUtil;
use Devis\DevisUtil;
use DevisHasCommandeHasArticle\DevisHasCommandeHasArticleUtil;
use Fournisseur\FournisseurUtil;
use Mail\OrderConfMail;
use Mail\OrderProviderConfMail;
use QuickPdo\QuickPdo;
use Sav\SavAjaxFormInsert;
use Sav\SavDetailsArrayRenderer;
use Sav\SavUtil;
use TypeContainer\TypeContainer;
use UniqueNameGenerator\Generator\ItemUniqueNameGenerator;

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
            if (
                array_key_exists('commande_id', $_GET) &&
                array_key_exists('json', $_POST)
            ) {
                $commandeId = $_GET['commande_id'];
                $usedContainers = json_decode($_POST['json'], true);

                $label2Ids = TypeContainer::getLabel2Id();

                try {

                    foreach ($usedContainers as $usedContainer) {


                        // create the container
                        $typeContainerId = $label2Ids[$usedContainer['name']];
                        $suggestedName = $usedContainer['suggestedName'];
                        $containerId = ContainerUtil::createContainer($suggestedName, $typeContainerId);;

                        // bind all items
                        $items = $usedContainer['items'];
                        foreach ($items as $item) {
                            $articleId = $item['aid'];
                            $fournisseurId = $item['fournisseur_id'];
                            CommandeHasArticleUtil::bindContainer($containerId, $commandeId, $articleId, $fournisseurId);
                        }
                    }
                    $output = "ok";
                } catch (\Exception $e) {
                    $output = $e->getMessage();
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
                array_key_exists('value', $_GET)
            ) {
                $col = $_GET['col'];
                $value = $_GET['value'];
                $ric = null;
                $fournisseurId = null;
                $articleId = null;


                if (array_key_exists('ric', $_GET)) {
                    $ric = $_GET['ric'];
                    list($commandeId, $articleId) = unric($ric);

                    $res = QuickPdo::update('commande_has_article', [
                        $col => $value,
                    ], [
                        ['commande_id', '=', $commandeId],
                        ['article_id', '=', $articleId],
                    ]);
                }
                if (array_key_exists('fid', $_GET) && array_key_exists('aid', $_GET)) {
                    $fournisseurId = $_GET['fid'];
                    $articleId = $_GET['aid'];

                    $res = QuickPdo::update('fournisseur_has_article', [
                        $col => $value,
                    ], [
                        ['article_id', '=', $articleId],
                        ['fournisseur_id', '=', $fournisseurId],
                    ]);
                }


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
                CommandeHasArticleUtil::updateStatutByCommandeId($commandeId, CommandeLigneStatutUtil::STATUT_DEUX_ENVOYE_PAR_MAIL_A_DIDIER);


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

                if (array_key_exists('test', $_GET)) {
                    $mail = MAIL_ZILU;
                } else {
                    $mail = FournisseurUtil::getEmail($providerId);
                }


                $output = [
                    'success' => 'ok',
                ];
                CommandeHasArticleUtil::updateStatutByCommandeIdProviderId($commandeId, $providerId, CommandeLigneStatutUtil::STATUT_TROIS_ENVOYE_PAR_MAIL_AUX_FOURNISSEURS);


                if (true === MAIL_ENABLE) {
                    try {
                        $n = OrderProviderConfMail::sendByCommandeIdFournisseurId($mail, $commandeId, $providerId, $signature);
                        if (1 === $n) {
                            $output = [
                                'success' => 'ok',
                            ];
                            CommandeHasArticleUtil::updateStatutByCommandeIdProviderId($commandeId, $providerId, CommandeLigneStatutUtil::STATUT_TROIS_ENVOYE_PAR_MAIL_AUX_FOURNISSEURS);

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
        case 'order-auto-repartition':
            if (array_key_exists('commande_id', $_GET)) {
                $commandeId = (int)$_GET['commande_id'];
                $isHtml = true;

                if (0 !== $commandeId) {

                    $overloadWarning = false;
                    try {
                        $usedContainers = CommandeToBinHelper::distributeCommandeById($commandeId);
                    } catch (WeightOverloadException $e) {
                        $usedContainers = $e->usedContainers;
                        $overloadWarning = true;
                    }

                    BinGuiUtil::decorateUsedContainers($usedContainers, $commandeId);

                    ob_start();
                    BinGuiUtil::displayDecoratedUsedContainers($usedContainers, $commandeId);
                    $output = ob_get_clean();

                } else {
                    $output = "";
                }
            }
            break;
        case 'display-container-items':
            if (
                array_key_exists('jsonItems', $_POST) &&
                array_key_exists('cid', $_GET) &&
                array_key_exists('coid', $_GET)
            ) {

                $jsonItems = json_decode($_POST['jsonItems'], true);
                $commandeId = (int)$_GET['cid'];
                $containerId = (int)$_GET['coid'];
                $isHtml = true;

                ob_start();
                BinGuiUtil::displayContainerItems($jsonItems);
                $output = ob_get_clean();
            }
            break;
        case 'update-container-article-column':
            $fournisseurId = null;
            $articleId = null;

            if (
                array_key_exists('col', $_GET) &&
                array_key_exists('value', $_GET) &&
                array_key_exists('fid', $_GET) &&
                array_key_exists('aid', $_GET)
            ) {
                $col = $_GET['col'];
                $value = $_GET['value'];
                $fournisseurId = $_GET['fid'];
                $articleId = $_GET['aid'];

                $res = QuickPdo::update('fournisseur_has_article', [
                    $col => $value,
                ], [
                    ['article_id', '=', $articleId],
                    ['fournisseur_id', '=', $fournisseurId],
                ]);
            }

            if (false !== $res) {
                $output = 'ok';
            } else {
                $output = 'ko';
            }

            break;
        /**
         * container left table works with the following session arrays:
         *
         * - container-commande-id
         * - container-container-ids   // for extra ids
         * - container-deleted-container-ids   // to substract from container-commande-id
         * - container-inactive-container-ids   // to substract from container-commande-id, but only the table on the right
         *
         */
        case 'container-commande-select':
            if (array_key_exists('cid', $_GET)) {
                $cid = $_GET['cid'];
                $containerIds = ContainerUtil::getContainerIdsByCommandeId($cid);
                $_SESSION['container-container-ids'] = $containerIds;
                $_SESSION['container-inactive-container-ids'] = [];
                $output = "ok";
            }
            break;
        case 'container-container-select':
            if (array_key_exists('id', $_GET)) {
                $_SESSION['container-container-ids'][] = $_GET['id'];
                $_SESSION['container-container-ids'] = array_unique($_SESSION['container-container-ids']);
                if (false !== ($index = array_search($_GET['id'], $_SESSION['container-inactive-container-ids']))) {
                    unset($_SESSION['container-inactive-container-ids'][$index]);
                }
                $output = "ok";
            }
            break;
        case 'container-container-delete':
            if (array_key_exists('id', $_GET)) {
                if (false !== ($index = array_search($_GET['id'], $_SESSION['container-container-ids']))) {
                    unset($_SESSION['container-container-ids'][$index]);
                    if (false !== ($index = array_search($_GET['id'], $_SESSION['container-inactive-container-ids']))) {
                        unset($_SESSION['container-inactive-container-ids'][$index]);
                    }
                }
                $output = "ok";
            }
            break;
        case 'container-container-inactive':
            if (array_key_exists('id', $_GET)) {
                $_SESSION['container-inactive-container-ids'][] = $_GET['id'];
                $_SESSION['container-inactive-container-ids'] = array_unique($_SESSION['container-inactive-container-ids']);
                $output = "ok";
            }
            break;
        case 'container-container-active':
            if (array_key_exists('id', $_GET)) {
                if (false !== ($index = array_search($_GET['id'], $_SESSION['container-inactive-container-ids']))) {
                    unset($_SESSION['container-inactive-container-ids'][$index]);
                }
                $output = "ok";
            }
            break;
        case 'container-summary-toggle':
            if (array_key_exists('active', $_GET)) {
                if (true === (bool)$_GET['active']) {
                    $_SESSION['container-inactive-container-ids'] = [];
                } else {
                    foreach ($_SESSION['container-container-ids'] as $id) {
                        $_SESSION['container-inactive-container-ids'][] = $id;
                        $_SESSION['container-inactive-container-ids'] = array_unique($_SESSION['container-inactive-container-ids']);
                    }
                }
                $output = "ok";
            }
            break;
        case 'container-summary-percent':
            if (array_key_exists('percent', $_GET)) {
                $_SESSION['summaryIsPercent'] = $_GET['percent'];
                $output = "ok";
            }
            break;
        case 'commande-update-statut':
            if (
                array_key_exists('statut', $_GET) &&
                array_key_exists('cid', $_GET) &&
                array_key_exists('aid', $_GET)
            ) {
                $statut = $_GET['statut'];
                $commandeId = $_GET['cid'];
                $articleId = $_GET['aid'];
                CommandeHasArticleUtil::updateStatut($commandeId, $articleId, $statut);
                $output = "ok";
            }
            break;
        case 'backups-newbackup':
            if (array_key_exists('relativepath', $_GET)) {
                $relativePath = str_replace('/', '', $_GET['relativepath']);
                AppBackup::create()->createBackup('manual/' . $relativePath);
                $output = "ok";
            }
            break;
        case 'commande-applydevis':
            if (
                array_key_exists('did', $_GET) &&
                array_key_exists('cid', $_GET)
            ) {
                $did = $_GET['did'];
                $cid = $_GET['cid'];

                DevisHasCommandeHasArticleUtil::bindDevisToCommande($did, $cid);
                $output = "ok";
            }
            break;
        case 'commande-devislist':
            if (
            array_key_exists('ric', $_GET)
            ) {
                $isHtml = true;
                $ric = $_GET['ric'];
                list($commandeId, $articleId) = unric($ric);
                CommandeHasArticleUtil::displayDevisTableByLine($commandeId, $articleId);
            }
            break;
        case 'devis-add-bindure':
            if (
                array_key_exists('did', $_GET) &&
                array_key_exists('cid', $_GET) &&
                array_key_exists('aid', $_GET)
            ) {
                $did = $_GET['did'];
                $cid = $_GET['cid'];
                $aid = $_GET['aid'];

                try {
                    DevisHasCommandeHasArticleUtil::insert($did, $cid, $aid);
                } catch (\Exception $e) {

                }

                ob_start();
                CommandeHasArticleUtil::displayDevisTableByLine($cid, $aid);
                $html = ob_get_clean();

                $nbDevis = DevisHasCommandeHasArticleUtil::getNbDevisPerLine($cid, $aid);
                $output = [
                    'html' => $html,
                    'nbDevis' => $nbDevis,
                ];

            }
            break;
        case 'devis-remove-bindure':
            if (
                array_key_exists('did', $_GET) &&
                array_key_exists('cid', $_GET) &&
                array_key_exists('aid', $_GET)
            ) {
                $did = $_GET['did'];
                $cid = $_GET['cid'];
                $aid = $_GET['aid'];

                DevisHasCommandeHasArticleUtil::remove($did, $cid, $aid);


                ob_start();
                CommandeHasArticleUtil::displayDevisTableByLine($cid, $aid);
                $html = ob_get_clean();
                $nbDevis = DevisHasCommandeHasArticleUtil::getNbDevisPerLine($cid, $aid);
                $output = [
                    'html' => $html,
                    'nbDevis' => $nbDevis,
                ];

            }
            break;
        case 'commande-exportcsv':
            if (
                array_key_exists('type', $_GET) &&
                array_key_exists('cid', $_GET)
            ) {
                $type = $_GET['type'];
                $cid = $_GET['cid'];

                if (false !== ($ref = CommandeUtil::getReferenceById($cid))) {
                    $file = APP_COMMANDE_EXPORTS_DIR . "/$ref.xlsx";
                    $_SESSION['download'] = $file;
                    CommandeExporterUtil::createCsvFileByCommande($file, $cid, $type);
                    $output = "ok";
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

