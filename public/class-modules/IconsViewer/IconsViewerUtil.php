<?php

namespace IconsViewer;


class IconsViewerUtil
{

    public static function getIconsList()
    {
        $content = file_get_contents(APP_ROOT_DIR . "/class-core/Icons/IconsFactory.php");

        $list = [];

        // matches the
        // case 'add':
        // like lines of the switch statement in the printIconsDefinitions
        $pattern = '!case (?:\\\'|")([a-zA-Z0-9-_]*)(?:\\\'|"):!';

        // matches the
        // <g id="add">
        // like lines
        $pattern = '!<g\s+id=(?:\\\'|")([a-zA-Z0-9-_]*)(?:\\\'|")\s*>!';


        if (preg_match_all($pattern, $content, $matches, \PREG_PATTERN_ORDER)) {
            $list = $matches[1];
        }
        return $list;

    }

}