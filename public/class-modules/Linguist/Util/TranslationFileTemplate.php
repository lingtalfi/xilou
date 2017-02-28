<?php


namespace Linguist\Util;


class TranslationFileTemplate
{

    public static function getContent(array $messages)
    {

        $sDefs = '';
        $s4 = '    ';
        foreach ($messages as $id) {
            $pr = str_replace('"', '\"', $id);
            $sDefs .= PHP_EOL . $s4 . '"' . $pr . '" => "' . $pr . '",';
        }
        $s = '<?php


$defs = [' . $sDefs . '
];

';
        return $s;
    }

    public static function getContentByDefs(array $defs)
    {

        $sDefs = '';
        $s4 = '    ';
        foreach ($defs as $id => $trans) {
            $id = str_replace('"', '\"', $id);
            $trans = str_replace('"', '\"', $trans);
            $sDefs .= PHP_EOL . $s4 . '"' . $id . '" => "' . $trans . '",';
        }
        $s = '<?php


$defs = [' . $sDefs . '
];

';
        return $s;
    }
}