<?php

namespace Shared\FrontOne\ArticleCrud;


use Shared\FrontOne\Ether;

class ArticleCrudConfig
{

    public static function getArticlesDir()
    {
        return Ether::get('FRONT_ROOT_DIR') . "/articles";
    }

    public static function getArticlesModelsDir()
    {
        return self::getArticlesDir() . "/models";
    }

}