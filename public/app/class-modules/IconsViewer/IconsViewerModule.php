<?php


namespace IconsViewer;


use Privilege\Privilege;

class IconsViewerModule
{
    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[IconsViewerConfig::getUri()] = IconsViewerConfig::getPage();
    }

    public static function displayToolsLeftMenuLinks()
    {

        $ll = "modules/iconsViewer/iconsViewer";
        if (Privilege::has('iconsViewer.access')):
            ?>
            <li>
                <a href="<?php echo IconsViewerConfig::getUri(); ?>"><?php echo __("IconsViewer", $ll); ?></a>
            </li>
            <?php
        endif;
    }
}