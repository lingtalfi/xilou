<?php


namespace NullosInfo;


use Privilege\Privilege;

class NullosInfoModule
{


    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[NullosInfoConfig::getUri()] = NullosInfoConfig::getPage();
    }


    public static function displayToolsLeftMenuLinks()
    {

        $ll = "modules/nullosInfo/nullosInfo";
        if (Privilege::has('nullosInfo.access')):
            ?>
            <li>
                <a href="<?php echo NullosInfoConfig::getUri(); ?>"><?php echo __("NullosInfo", $ll); ?></a>
            </li>
            <?php
        endif;
    }

}