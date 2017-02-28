<?php


namespace QuickDoc;

use Layout\AssetsList;
use Privilege\Privilege;

/**
 * Are you creating a doc?
 *
 * If not, well, come back later, when you will be writing a documentation.
 *
 *
 * Are you creating a doc?
 *
 * If yes, then meet the QuickDoc module.
 */
class QuickDocModule
{
    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[QuickDocConfig::getUri()] = QuickDocConfig::getPage();
    }

    public static function registerAssets(AssetsList $assetsList)
    {
        if (QuickDocConfig::getUri() === \Spirit::get('uri')) {
            $assetsList->css('/style/tabby.css');
        }
    }

    public static function displayToolsLeftMenuLinks()
    {

        $ll = "modules/quickDoc/quickDoc";
        if (Privilege::has('quickDoc.access')):
            ?>
            <li>
                <a href="<?php echo QuickDocConfig::getUri(); ?>"><?php echo __("QuickDoc", $ll); ?></a>
            </li>
            <?php
        endif;
    }


}