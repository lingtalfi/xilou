<?php


namespace Counter;


use Privilege\Privilege;


/**
 * General
 * ------------
 * This module can install itself on any target (i.e. directory).
 * The installation is done when the following conditions are fulfilled:
 *
 * - the Counter module is present in the class-modules directory
 *          of the front.
 * - the onPageRenderedAfter method of the CounterModule class
 *          subscribes to the Events module service of the same name.
 * - the /stats-counter directory exist in the front
 *
 *
 *
 *
 *
 *
 */
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