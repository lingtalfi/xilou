<?php


use Bat\FileSystemTool;
use FrontOne\ArticleCrud\ArticleCrudConfig;

require_once __DIR__ . "/../../../../init.php";

/**
 * General note on services:
 * they are insecure, every data should be parsed with some degree of paranoia,
 * like forms data.
 */
if (array_key_exists('model', $_POST)) {
    $path = (string)$_POST['model'];
    $modelDir = ArticleCrudConfig::getArticlesModelsDir();
    $model = $modelDir . "/" . $path;

    if (FileSystemTool::existsUnder($model, $modelDir)) {
        echo file_get_contents($model);
    }
}