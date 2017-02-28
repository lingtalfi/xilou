<?php


namespace Counter;


use Privilege\Privilege;

class CounterModule
{
    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[CounterConfig::getUri()] = CounterConfig::getPage();
    }

    public static function displayToolsLeftMenuLinks()
    {

        $ll = "modules/counter/counter";
        if (Privilege::has('counter.access')):
            ?>
            <li>
                <a href="<?php echo CounterConfig::getUri(); ?>"><?php echo __("Counter", $ll); ?></a>
            </li>
            <?php
        endif;
    }

    public static function onPageRenderedAfter()
    {
        CounterUtil::capture();
    }
}