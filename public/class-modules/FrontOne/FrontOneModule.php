<?php


namespace FrontOne;

use Layout\LayoutHelper;
use Privilege\Privilege;

/**
 * FrontOne is a module responsible for administering a
 * front website which contains only one page with multiple tabs.
 *
 * Each tab is an "article".
 * The main theme is configured via the dedicated "theme" left menu item.
 *
 *
 */
class FrontOneModule
{

    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[FrontOneConfig::getThemeUri()] = FrontOneConfig::getThemePage();
        $uri2pagesMap[FrontOneConfig::getArticlesUri()] = FrontOneConfig::getArticlesPage();
        $uri2pagesMap[FrontOneConfig::getSocialUri()] = FrontOneConfig::getSocialPage();
    }

    public static function displayLeftMenuBlocks()
    {
        $ll = "modules/frontOne/frontOne";

        if (Privilege::has('frontOne.access')):
            ?>
            <section class="section-block front-one">
                <?php LayoutHelper::displayLeftMenuExpandableTitle(__("Front", $ll)); ?>
                <ul class="linkslist">
                    <li><a href="<?php echo FrontOneConfig::getThemeUri(); ?>"><?php echo __("Theme", $ll); ?></a></li>
                    <li><a href="<?php echo FrontOneConfig::getArticlesUri(); ?>"><?php echo __("Articles", $ll); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo FrontOneConfig::getSocialUri(); ?>"><?php echo __("Social links", $ll); ?></a>
                    </li>
                </ul>
            </section>
            <?php
        endif;

    }

    public static function getFrontWebsites(array &$fronts)
    {
        $fronts['FrontOne'] = APP_ROOT_DIR . "/../app-vitrine-one";
    }


}