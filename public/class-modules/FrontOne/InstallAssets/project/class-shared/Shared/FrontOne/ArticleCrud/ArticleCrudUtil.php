<?php


namespace Shared\FrontOne\ArticleCrud;


use Bat\FileSystemTool;
use Shared\FrontOne\Article;
use Shared\FrontOne\ArticleCrud\Exception\ArticleCrudCannotDeleteProtectedException;
use Shared\FrontOne\ArticleCrud\Exception\ArticleCrudException;
use Shared\FrontOne\ArticleList;

class ArticleCrudUtil
{

    public static function create(Article $article, $forceIfAnchor = null)
    {
        $list = new ArticleList();
        ArticleScannerUtil::getAllArticles($list);
        if (null !== ($oldArticle = $list->getArticle($article->getAnchor()))) {
            if ($forceIfAnchor !== $article->getAnchor()) {
                throw new ArticleCrudException("Article " . $article->getAnchor() . " already exists");
            }
        }

        $dir = ArticleCrudConfig::getArticlesDir();
        $file = $dir . "/" . $article->getAnchor() . '.php';
        $contentFile = $dir . "/content/" . $article->getAnchor() . '.php';
        FileSystemTool::mkfile($contentFile, $article->getContent());

        $model = '
<?php


use Shared\FrontOne\FileArticle;

$article = new FileArticle();
$article->setAnchor("' . $article->getAnchor() . '");
$article->setLabel("' . str_replace('"', '\"', $article->getLabel()) . '");
$article->setPosition(' . $article->getPosition() . ');
$article->setIsProtected(' . ((true === $article->isProtected()) ? 'true' : 'false') . ');
$article->setIsActive(' . ((true === $article->isActive()) ? 'true' : 'false') . ');
$article->setFile(__DIR__ . "/content/' . $article->getAnchor() . '.php");        
        ';
        file_put_contents($file, $model);

    }

    public static function replace($anchor, Article $article)
    {
        self::create($article, $anchor);
        if ($anchor !== $article->getAnchor()) {
            self::delete($anchor);
        }
    }


    public static function delete($anchor)
    {
        $list = new ArticleList();
        ArticleScannerUtil::getAllArticles($list);
        if (null !== ($article = $list->getArticle($anchor))) {
            if (false === $article->isProtected()) {

                $dir = ArticleCrudConfig::getArticlesDir();
                $file = $dir . "/" . $anchor . '.php';
                $contentFile = $dir . "/content/" . $anchor . '.php';
                if (FileSystemTool::existsUnder($file, $dir)) {
                    unlink($file);
                    unlink($contentFile);
                }
                return true;
            }
            $anchor = $article->getAnchor();
            throw new ArticleCrudCannotDeleteProtectedException("Article $anchor is protected and therefore cannot be deleted");
        }
        return false;
    }

}