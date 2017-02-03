<?php

namespace Layout;

use AssetsList\AssetsList;
use Shared\FrontOne\ArticleList;
use Shared\FrontOne\FileArticle;
use Shared\FrontOne\FrontOneUtil;

class Layout
{

    private $elementFiles;
    private $elementsDir;

    protected $onDisplayBefore;


    protected function __construct()
    {
        $this->elementsDir = APP_ROOT_DIR . "/layout-elements";
    }

    public static function create()
    {
        return new self;
    }


    /**
     * array of elementType => fileName
     */
    public function setElementFiles(array $files)
    {
        $this->elementFiles = $files;
        return $this;
    }

    public function display()
    {
        if (is_callable($this->onDisplayBefore)) {
            call_user_func($this->onDisplayBefore, $this);
        }

        $exception = null;
        ob_start();

        $articlesList = new ArticleList();
        LayoutBridge::registerArticles($articlesList);
        $articles = $articlesList->getArticles();

        $theme = FrontOneUtil::getTheme();

        ?>
        <body>

        <!-- Wrapper -->
        <div id="wrapper">

            <!-- Header -->
            <header id="header">
                <div class="logo">
                    <span class="icon <?php echo $theme['icon']; ?>"></span>
                </div>
                <div class="content">
                    <div class="inner">
                        <h1><?php echo $theme['title']; ?></h1>
                        <p><?php echo $theme['headerParagraph']; ?></p>
                    </div>
                </div>
                <nav>
                    <ul>
                        <?php foreach ($articles as $article): ?>
                            <?php if (true === $article->isActive()): ?>
                                <li>
                                    <a href="#<?php echo $article->getAnchor(); ?>"><?php echo $article->getLabel(); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </header>

            <!-- Main -->
            <div id="main">
                <?php
                foreach ($articles as $article) {
                    ?>
                    <?php if (true === $article->isActive()): ?>
                        <article id="<?php echo $article->getAnchor(); ?>"><?php
                            if ($article instanceof FileArticle) {
                                require $article->getFile();
                            } else {
                                echo $article->getContent();
                            }
                            ?>
                        </article>
                    <?php endif; ?>
                    <?php
                }
                ?>
            </div>

            <!-- Footer -->
            <footer id="footer">
                <p class="copyright"><?php echo $theme['footerParagraph']; ?></p>
            </footer>

        </div>

        <!-- BG -->
        <div id="bg"></div>

        <!-- Scripts -->
        <script src="<?php echo url('assets/js/jquery.min.js'); ?>"></script>
        <script src="<?php echo url('assets/js/skel.min.js'); ?>"></script>
        <script src="<?php echo url('assets/js/util.js'); ?>"></script>
        <script src="<?php echo url('assets/js/main.js'); ?>"></script>

        </body>
        <?php
        $body = ob_get_clean();
        ?>
        <!DOCTYPE HTML>
        <!--
            Dimension by HTML5 UP
            html5up.net | @ajlkn
            Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
        -->
        <html>
        <head>
            <title><?php echo $theme['seoTitle']; ?></title>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
            <link rel="stylesheet" href="<?php echo url('assets/css/main.css'); ?>"/>
            <!--[if lte IE 9]>
            <link rel="stylesheet" href="<?php echo url('assets/css/ie9.css'); ?>"/><![endif]-->
            <noscript>
                <link rel="stylesheet" href="<?php echo url('assets/css/noscript.css'); ?>"/>
            </noscript>
            <?php
            AssetsList::displayList();
            ?>
        </head>
        <?php echo $body; ?>
        </html>
        <?php
    }


    private function includeElement($key)
    {
        $fileName = $this->elementFiles[$key];
        $file = $this->elementsDir . "/" . $fileName;
        require_once $file;
    }
}