<?php


namespace CsvImport;


use Article\Article;
use Commande\CommandeUtil;
use Fournisseur\FournisseurUtil;
use FournisseurHasArticle\FournisseurHasArticle;
use QuickPdo\QuickPdo;
use Util\GeneralUtil;
use Util\RowsRenderer;

class CommandeImporterUtil
{


    /**
     * Process the data file,
     * and returns the number of successfully parsed lines.
     */
    public static function createCommandeByCsvFile($csvFile, $commandeName, array &$missingArticles = [])
    {

        $ret = false;
        if (false !== ($idCommande = CommandeUtil::insertCommande([
                "reference" => $commandeName,
            ]))
        ) {
            $ret = $idCommande;
            self::start();
            $o = \PHPExcel_IOFactory::load($csvFile);
            $sheet = $o->getActiveSheet();
            $rows = $sheet->toArray();


            $statusId = 1; // pas encore traité


            foreach ($rows as $row) {
                if (is_float($row[0])) {


                    $ref_lf = (string)$row[0];
                    $fournisseurNom = (string)$row[1];
                    $prixUnitDollar = GeneralUtil::toDecimal($row[4]);
                    $quantite = (int)$row[5];
                    $unit = (string)$row[6];


                    if (false !== ($article = Article::getArticleByRef($ref_lf))) {
                        $idArticle = $article['id'];
                        if (false !== ($fournisseur = FournisseurUtil::getFournisseurByNom($fournisseurNom))) {
                            $idFournisseur = $fournisseur['id'];

                            QuickPdo::insert('commande_has_article', [
                                'commande_id' => $idCommande,
                                'article_id' => $idArticle,
                                'container_id' => null,
                                'fournisseur_id' => $idFournisseur,
                                'sav_id' => null,
                                'commande_ligne_statut_id' => $statusId,
                                'prix_override' => $prixUnitDollar,
                                'date_estimee' => null,
                                'quantite' => $quantite,
                                'unit' => $unit,
                            ]);
                        }
                    } else {
                        $missingArticles[] = $ref_lf;
                        if (false !== ($id = Article::insertByRef($ref_lf, $row[2]))) {
                            if (false !== ($fournisseur = FournisseurUtil::getFournisseurByNom($fournisseurNom))) {
                                $idFournisseur = $fournisseur['id'];
                                FournisseurHasArticle::insertEmpty($idFournisseur, $id);
                            }
                        }
                    }
                }
            }
        }
        return $ret;
    }

    public static function renderCsv($f)
    {
        self::start();
        $o = \PHPExcel_IOFactory::load($f);
        $sheet = $o->getActiveSheet();
        $a = $sheet->toArray();
        RowsRenderer::create()->setValues($a)->render();
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    private static function start()
    {
        require_once __DIR__ . '/../../www/PHPExcel/Classes/PHPExcel/IOFactory.php';
    }
}