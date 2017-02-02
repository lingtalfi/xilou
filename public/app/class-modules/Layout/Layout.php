<?php

namespace Layout;

use AssetsList\AssetsList;
use Icons\Icons;
use Icons\IconsFactory;

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
        ?>
        <body>
        <div class="site">
            <div class="menutop">
                <ul>
                    <li><a href="/fournisseur">Fournisseur</a></li>
                    <li><a href="/commande">Commande</a></li>
                    <li><a href="/container">Container</a></li>
                </ul>
            </div>
            <div class="body">
                <?php
                try {
                    // we just prevent the exception from breaking the ob_start
                    $this->includeElement('body');
                } catch (\Exception $exception) {
                    if (false === LayoutConfig::showBodyExceptionInYourFace()) {
                        Goofy::alertError(\Helper::defaultLogMsg(), false, false);
                        \Logger::log($exception, "layout.body");
                    } else {
                        a($exception);
                    }
                }
                ?>
            </div>
            <div class="footer"></div>
        </div>

        <?php IconsFactory::printIconsDefinitions(); ?>

        </body>
        <?php
        $body = ob_get_clean();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title><?php echo ucfirst(WEBSITE_NAME); ?></title>
            <link rel="stylesheet" href="<?php echo url('/styles/style.css'); ?>">
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