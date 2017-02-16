<?php


namespace DbTransition;


use QuickPdo\QuickPdo;

class Article
{


    public static function createArticles()
    {

        $items = QuickPdo::fetchAll('
select 
reference, 
reference_fournisseur,
fournisseur,
produits as label_fr,
libelle_origine as label_en,
unit,
pmp_achat_dollar,
poids,
dimensions,
description,
code_ean


from csv_prix_materiel

');


//        QuickPdo::delete('article');

        foreach ($items as $item) {
            $ref = trim($item['reference']);
            if ('' !== $ref) {

                $hldp = "";
                if (false !== ($res = QuickPdo::fetch("select ref_hldp from csv_product_list where ref_lf=:ref", [
                        'ref' => $ref,
                    ]))
                ) {
                    $hldp = $res['ref_hldp'];
                }

                $labelEn = '';


                QuickPdo::insert('article', [
                    'reference_lf' => $ref,
                    'reference_hldp' => $hldp,
                    'poids' => $item['poids'],
                    'descr_fr' => $item['label_fr'],
                    'descr_en' => $labelEn,
                ]);
            }
        }


    }
}