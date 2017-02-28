<?php


namespace LogWatcher;


use Privilege\Privilege;

class LogWatcherModule
{
    public static function decorateUri2PagesMap(array &$uri2pagesMap)
    {
        $uri2pagesMap[LogWatcherConfig::getUri()] = LogWatcherConfig::getPage();
    }

    public static function displayToolsLeftMenuLinks()
    {

        $ll = "modules/logWatcher/logWatcher";
        if (Privilege::has('logWatcher.access')):
            ?>
            <li>
                <a href="<?php echo LogWatcherConfig::getUri(); ?>"><?php echo __("LogWatcher", $ll); ?></a>
            </li>
            <?php
        endif;
    }
}