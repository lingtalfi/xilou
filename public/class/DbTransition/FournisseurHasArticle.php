<?php


namespace DbTransition;


use QuickPdo\QuickPdo;
use Util\GeneralUtil;

class FournisseurHasArticle
{


    public static function createBindures()
    {

        self::fallbackMethod();


    }


    private static function fallbackMethod()
    {
        $fournisseurIds = Fournisseur::getFournisseurIds();


        $items = QuickPdo::fetchAll("
select fournisseur, ref_hldp, ref, unit, unit_price, m3_unit, poids_unit from csv_fournisseurs_fournisseurs f where ref!=''
");
        foreach ($items as $item) {
            $fournisseur = $item['fournisseur'];
            $refHldp = $item['ref_hldp'];
            if (false !== ($res = QuickPdo::fetch('select id from article where reference_hldp=:ref', [
                    'ref' => $refHldp,
                ]))
            ) {
                if (array_key_exists($fournisseur, $fournisseurIds)) {


                    $fId = $fournisseurIds[$fournisseur];
                    $ins = [
                        'fournisseur_id' => $fId,
                        'article_id' => $res['id'],
                        'reference' => $item['ref'],
                        'prix' => GeneralUtil::toDecimal($item['unit_price']),
                        'volume' => GeneralUtil::toDecimal($item['m3_unit']),
                        'poids' => GeneralUtil::toDecimal($item['poids_unit']),
                    ];
                    self::replaceData($ins);
                }
            }
        }
    }


    private static function fallbackMethod2()
    {

        $items = QuickPdo::fetchAll("
select reference, reference_fournisseur, fournisseur, unit, pmp_achat_dollar, m3 
from csv_prix_materiel
");
        foreach ($items as $item) {
            if (false !== ($res = QuickPdo::fetch("select id from fournisseur where nom=:nom", [
                    'nom' => $item['fournisseur'],
                ]))
            ) {
                $fournisseurId = $res['id'];

                if (false !== ($res2 = QuickPdo::fetch('select id from article where reference_lf=:ref', [
                        'ref' => $item['reference_fournisseur'],
                    ]))
                ) {
                    $articleId = $res2['id'];
                    $data = [
                        'article_id' => $articleId,
                        'fournisseur_id' => $fournisseurId,
                        "reference" => $item['reference'],
                        "prix" => $item['pmp_achat_dollar'],
                        "volume" => $item['m3'],
                    ];
                    self::replaceData($data);
                }
            }
        }
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/


    private static function replaceData(array $item)
    {

        $fournisseurId = $item['fournisseur_id'];
        $articleId = $item['article_id'];


        if (false === ($res3 = QuickPdo::fetch("select fournisseur_id from fournisseur_has_article where
 fournisseur_id=" . (int)$fournisseurId . " and article_id=" . (int)$articleId))
        ) {
            QuickPdo::insert("fournisseur_has_article", [
                "fournisseur_id" => $fournisseurId,
                "article_id" => $articleId,
                "reference" => $item['reference'],
                "prix" => $item['prix'],
                "volume" => $item['volume'],
                "poids" => $item['poids'],
            ]);
        } else {
            QuickPdo::update("fournisseur_has_article", [
                "reference" => $item['reference'],
                "prix" => $item['prix'],
                "volume" => $item['volume'],
                "poids" => $item['poids'],
            ], [
                ['fournisseur_id', '=', $fournisseurId],
                ['article_id', '=', $articleId],
            ]);
        }
    }
}