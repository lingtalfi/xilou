<?php


namespace Layout;

use Shared\FrontOne\ArticleList;
use Shared\FrontOne\ArticleCrud\ArticleCrudModule;

class LayoutBridge
{

    /**
     * Owned by:
     * - class/Layout
     */
    public static function registerArticles(ArticleList $list)
    {
        ArticleCrudModule::registerArticles($list);
    }

}