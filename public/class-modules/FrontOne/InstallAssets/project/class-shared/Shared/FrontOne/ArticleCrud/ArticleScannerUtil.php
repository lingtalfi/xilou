<?php


namespace Shared\FrontOne\ArticleCrud;


use Bat\FileSystemTool;
use DirScanner\YorgDirScannerTool;
use Shared\FrontOne\ArticleList;

class ArticleScannerUtil
{


    public static function getAllArticles(ArticleList $list = null)
    {
        if (null === $list) {
            $list = new ArticleList();
        }
        $dir = ArticleCrudConfig::getArticlesDir();
        $files = scandir($dir);
        foreach ($files as $file) {
            if ('.' !== $file && '..' !== $file) {
                if ('php' === strtolower(FileSystemTool::getFileExtension($file))) {
                    $article = null;
                    $realFile = $dir . '/' . $file;
                    require $realFile;
                    $list->addArticle($article);
                }
            }
        }
        return $list;
    }


    public static function getModels()
    {
        $dir = ArticleCrudConfig::getArticlesModelsDir();
        $ret = [];
        $files = YorgDirScannerTool::getFilesWithExtension($dir, 'php', false, true, true);
        foreach ($files as $file) {
            $ret[$file] = $file;
        }
        return $ret;
    }


}