<?php


namespace Article;


use QuickPdo\QuickPdo;

class Article
{

    public static function getArticleByRef($ref)
    {
        return QuickPdo::fetch('select * from article where reference_lf=:ref', [
            'ref' => $ref,
        ]);
    }


    /**
     * Called from zilu.php service
     *
     * The idea behind unknown is: you can search for them in the database,
     * so basically you can differentiate empty results from results creating
     * with THIS method
     *
     */
    public static function insertByRef($ref_lf, $descrEn = "")
    {
        return QuickPdo::insert('article', [
            'reference_lf' => $ref_lf,
            'reference_hldp' => "unknown",
            'descr_fr' => "",
            'descr_en' => $descrEn,
            'ean' => "unknown",
            'long_desc_en' => "",
        ]);
    }

    public static function getLogoList()
    {
        $ret = [
            "/Products-list.fld/image034.png",
            "/Products-list.fld/image051.png",
            "/Products-list.fld/image427.png",
            "/Products-list.fld/image496.png",
        ];
//        $list = QuickPdo::fetchAll("select logo from article where logo != '/' and logo != '' order by logo asc", [], \PDO::FETCH_COLUMN);
//        foreach ($list as $v) {
//            $ret[$v] = $v;
//        }
        return $ret;
    }

}