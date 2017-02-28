<?php

namespace Shared\FrontOne;


class ArticleList
{
    private $articles;


    public function __construct()
    {
        $this->articles = [];
    }

    public function addArticle(Article $article)
    {
        $this->articles[$article->getAnchor()] = $article;
        return $this;
    }

    /**
     * @return Article
     */
    public function getArticle($anchor)
    {
        if (array_key_exists($anchor, $this->articles)) {
            return $this->articles[$anchor];
        }
        return null;
    }

    /**
     * @return Article
     */
    public function removeArticle($anchor)
    {
        unset($this->articles[$anchor]);
    }

    public function getArticles()
    {
        $articles = array_merge($this->articles);
        usort($articles, function ($art1, $art2) {
            return ($art1->getPosition() > $art2->getPosition());
        });
        return $articles;
    }

}


