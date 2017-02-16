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
produits as label_fr,
code_ean


from csv_prix_materiel

');


//        QuickPdo::delete('article');

        foreach ($items as $item) {
            $ref = trim($item['reference']);
            if ('' !== $ref) {

                $hldp = "";
                $ean = $item['code_ean'];
                if (false !== ($res = QuickPdo::fetch("select ref_hldp from csv_product_list where ref_lf=:ref", [
                        'ref' => $ref,
                    ]))
                ) {
                    $hldp = $res['ref_hldp'];
                }

                $labelEn = '';
                if (false !== ($res = QuickPdo::fetch('select product, ean from csv_product_details where ref=:ref', [
                        'ref' => $ref,
                    ]))
                ) {
                    $labelEn = $res['product'];
                    if ('' !== $res['ean']) {
                        $ean = $res['ean'];
                    }
                }


                if ('' === $ean) {
                    if (false !== ($res = QuickPdo::fetch('select code_ean from csv_prix_materiel where reference=:ref', [
                            'ref' => $ref,
                        ]))
                    ) {
                        if ('' !== trim($res['code_ean'])) {
                            $ean = trim($res['code_ean']);
                        }
                    }
                }


                QuickPdo::insert('article', [
                    'reference_lf' => $ref,
                    'reference_hldp' => $hldp,
                    'descr_fr' => $item['label_fr'],
                    'descr_en' => $labelEn,
                    'ean' => $ean,
                ]);
            }
        }


    }
}