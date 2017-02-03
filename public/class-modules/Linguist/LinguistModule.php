<?php


namespace Linguist;


use Privilege\Privilege;

class LinguistModule
{


    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[LinguistConfig::getUri()] = LinguistConfig::getPage();
    }


    public static function displayToolsLeftMenuLinks()
    {

        $ll = "modules/linguist/linguist";
        if (Privilege::has('linguist.access')):
            ?>
            <li>
                <a href="<?php echo LinguistConfig::getUri(); ?>"><?php echo __("Linguist", $ll); ?></a>
            </li>
            <?php
        endif;
    }

}