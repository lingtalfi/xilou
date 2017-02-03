<?php

namespace FrontOne\Util;

use Shared\FrontOne\ArticleList;

class ArticlesUtil
{
    public static function articlesListToArray(ArticleList $list)
    {
        $articles = $list->getArticles();
        $articlesArr = [];
        foreach ($articles as $article) {
            $articlesArr[] = [
                'anchor' => $article->getAnchor(),
                'label' => $article->getLabel(),
                'content' => $article->getContent(),
                'active' => (int)$article->isActive(),
                'protected' => (int)$article->isProtected(),
                'position' => $article->getPosition(),
            ];
        }
        return $articlesArr;
    }

}