<?php


namespace Linguist\Util;


use DirScanner\YorgDirScannerTool;
use Linguist\LinguistConfig;
use SequenceMatcher\Element\Group;
use SequenceMatcher\Model;
use SequenceMatcher\SequenceMatcher;
use Tokens\SequenceMatcher\Element\TokenEntity;
use Tokens\SequenceMatcher\Element\TokenGreedyEntity;
use Tokens\SequenceMatcher\Util\TokensSequenceMatcherUtil;

class LinguistScanner
{


    public static function getLangNames()
    {
        $langDir = LinguistConfig::getLangDir();
        return YorgDirScannerTool::getDirs($langDir, false, true);
    }


    /**
     * An item is an array with the following entries:
     * - 0: file, relative path to the file containing the $defs
     * - 1: key (the identifier of the message)
     * - 2: value (the translated message)
     * - 3: isSameRefLang, a boolean indicating whether or not the value is the same as the refLang's value
     *          - if the value (for some reasons), only exist in the dst dir, but not in the refLang dir,
     *                  then isLikeRefLang will be false, to point out differences between both directories
     *
     *
     *
     * It returns an array of file => items
     *
     */
    public static function getDefinitionItems($lang, $refLang = "en")
    {

        $ret = [];
        $langDir = LinguistConfig::getLangDir();

        $dir = $langDir . "/" . $lang;
        $refDir = $langDir . "/" . $refLang;


        if (file_exists($dir)) {
            $files = YorgDirScannerTool::getFilesWithExtension($dir, "php", false, true, true);
            foreach ($files as $file) {
                $realFile = $dir . "/" . $file;
                $defs = [];
                require $realFile;
                $targetDefs = $defs;

                $realRefFile = $refDir . "/" . $file;
                $refDefs = [];
                if (file_exists($realRefFile)) {
                    $defs = [];
                    require $realRefFile;
                    $refDefs = $defs;
                }

                foreach ($targetDefs as $k => $v) {
                    $isLikeRef = false;
                    if (array_key_exists($k, $refDefs) && $v === $refDefs[$k]) {
                        $isLikeRef = true;
                    }
                    $ret[$file][] = [$file, $k, $v, $isLikeRef];
                }
            }
        }
        return $ret;
    }


    public static function scanTranslationsByDir($dir)
    {
        $ret = [];
        $files = YorgDirScannerTool::getFiles($dir, true, true);
        foreach ($files as $file) {
            $absFile = $dir . '/' . $file;
            $translations = self::scanTranslationsByFile($absFile);
            foreach ($translations as $trans) {
                $ret[] = $trans;
            }
        }
        return $ret;
    }


    public static function scanTranslationsByFile($file)
    {

        $ret = [];

        $tokens = token_get_all(file_get_contents($file));

        $model = Model::create()
            ->addElement(TokenEntity::create(T_STRING, '__'))
            ->addElement(TokenEntity::create(T_WHITESPACE, null), '?')
            ->addElement(TokenEntity::create(null, '('))
            ->addElement(TokenEntity::create(T_WHITESPACE, null), '?')
            ->addElement(TokenEntity::create(T_CONSTANT_ENCAPSED_STRING, null), null, 'id')
            ->addElement(TokenEntity::create(T_WHITESPACE, null), '?')
            ->addElement(Group::create(null)
                    ->addElement(TokenEntity::create(null, ','))
                    ->addElement(TokenEntity::create(T_WHITESPACE, null), '?')
                    ->addElement(TokenEntity::create(T_CONSTANT_ENCAPSED_STRING, null), null, 'context')
                    ->addElement(TokenEntity::create(T_WHITESPACE, null), '?')
                , '?'
            )
            ->addElement(TokenGreedyEntity::create(null, ')'), '*')
            ->addElement(TokenEntity::create(null, ')'));

        $sequence = $tokens;

        $markers = [];
        SequenceMatcher::create()
            ->match($sequence, $model, function (array $matchedElements, array $matchedThings, array $_markers = null) use (&$markers) {
                $markers[] = TokensSequenceMatcherUtil::detokenizeMarkers($_markers);
            });

        foreach ($markers as $info) {
            $arr = [
                'id' => array_shift($info['id']),
            ];
            if (array_key_exists('context', $info)) {
                $arr['context'] = array_shift($info['context']);
            }
            $ret[] = $arr;
        }
        return $ret;
    }

}