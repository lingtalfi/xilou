<?php


namespace Shared\FrontOne\ArticleCrud;


use Shared\FrontOne\ArticleList;

class ArticleCrudModule
{
    public static function registerArticles(ArticleList $list)
    {
        ArticleScannerUtil::getAllArticles($list);
    }

}