<?php


namespace Linguist\Util;


use Bat\FileSystemTool;
use DirScanner\YorgDirScannerTool;
use Linguist\LinguistException;
use Tokens\TokenRepresentation\ReplacementSequence;
use Tokens\TokenRepresentation\ReplacementSequenceToken;
use Tokens\TokenRepresentation\TokenRepresentation;
use Tokens\Tokens;
use Tokens\Util\TokenUtil;

class LinguistEqualizer
{

    public static function countMissingDefinitions($dstDir, $refDir)
    {
        $n = 0;
        if (file_exists($refDir)) {
            if (file_exists($dstDir)) {
                $files = YorgDirScannerTool::getFilesWithExtension($refDir, 'php', false, true, true);
                foreach ($files as $relPath) {

                    $refFile = $refDir . "/" . $relPath;
                    $dstFile = $dstDir . "/" . $relPath;

                    $defs = [];
                    require $refFile;
                    $refDefs = $defs;

                    if (file_exists($dstFile)) {
                        $defs = [];
                        require $dstFile;
                        $diff = array_diff_key($refDefs, $defs);
                        $n += count($diff);

                    } else {
                        $n += count($refDefs);
                    }
                }
            } else {
                throw new LinguistException("dstDir does not exist: $dstDir");
            }
        } else {
            throw new LinguistException("refDir does not exist: $refDir");
        }
        return $n;
    }


    /**
     *
     * Basically, it's used as an "import from en" button in a gui.
     *
     * It will create the missing entries from the src dir into the dst dir.
     *
     *
     * - Will
     *      - copy files that are present in srcDir but not in dstDir
     *      - if file exist but is not complete (missing $defs entries)
     *              - the src file will be used as a model to complete the dst file
     *              - comments in the src file should be preserved, as they can serve organizational purposes
     *
     *
     * Note: the src dir is considered as THE ONLY reference.
     * If your dst dir contains extra definitions that are NOT in the src dir, they will be removed.
     *
     * Returns false if at least one file couldn't be imported, true otherwise, true otherwise (if all files
     * have been successfully imported).
     *
     */
    public static function equalize($srcDir, $dstDir)
    {
        $ret = false;
        if (file_exists($srcDir)) {
            if (file_exists($dstDir)) {
                $ret = true;

                $files = YorgDirScannerTool::getFilesWithExtension($srcDir, 'php', false, true, true);

                foreach ($files as $relPath) {

                    $srcFile = $srcDir . "/" . $relPath;
                    $dstFile = $dstDir . "/" . $relPath;

                    if (file_exists($dstFile)) {
                        if (false === self::copyWithComments($srcFile, $dstFile)) {
                            $ret = false;
                        }
                    } else {
                        FileSystemTool::mkdir(dirname($dstFile), 0777, true);
                        if (false === copy($srcFile, $dstFile)) {
                            $ret = false;
                        }
                    }

                }
            } else {
                throw new LinguistException("dstDir does not exist: $dstDir");
            }
        } else {
            throw new LinguistException("srcDir does not exist: $srcDir");
        }
        return $ret;
    }


    public static function equalizeByFile2Definitions($refDir, $dstDir, array $file2Definitions)
    {
        $ret = true;
        if (file_exists($refDir)) {
            if (file_exists($dstDir)) {


                foreach ($file2Definitions as $relPath => $defs) {

                    $srcFile = $refDir . "/" . $relPath;
                    $dstFile = $dstDir . "/" . $relPath;


                    // file2Definitionss comes from $_POST, just do basic checking...
                    if (FileSystemTool::existsUnder($srcFile, $refDir)) {
                        if (FileSystemTool::existsUnder($dstFile, $dstDir)) {
                            if (false === self::copyWithComments($srcFile, $dstFile, $defs)) {
                                $ret = false;
                            }
                        }
                    }
                }
            } else {
                throw new LinguistException("dstDir does not exist: $dstDir");
            }
        } else {
            throw new LinguistException("refDir does not exist: $refDir");
        }
        return $ret;
    }


    /**
     *
     * Will copy the srcFile's definitions into the dstFile, keeping comments.
     *
     *      - definitions is an array of key (identifier) => value (translated string)
     *
     *
     * The third parameter, defs, can be used to replace parts or all
     * of the dstFile definitions.
     *
     *
     * If a srcFile's definition is not present in the dstFile's definitions nor passed
     * with the defs parameter, then the original srcFile's definition value will
     * be used.
     *
     *
     * Note: this technique doesn't work in some cases where the translation spans multiple lines,
     * for instance with a long line:
     *          "oops, something wrong happened with the form, please fix the form errors then submit again"
     *          => "oops, something wrong happened with the form, please fix the form errors then submit again",
     *
     * So, todo: update this technique (maybe use the new TokenSequenceMatcher?)
     *
     */
    public static function copyWithComments($srcFile, $dstFile, array $defs = [])
    {
        $_defs = $defs;
        if (file_exists($dstFile)) {
            $defs = [];
            require $dstFile;
            $defs = array_replace($defs, $_defs);
        }


        $tokenIdentifiers = token_get_all(file_get_contents($srcFile));
        $representation = self::getMessageLineTokenRepresentation($tokenIdentifiers, $_defs);
        $representation->onSequenceMatch(function ($newSeq) use ($defs) {
            $key = $newSeq[0][1];
            $trueKey = TokenUtil::deEncapsulate($key);
            if (array_key_exists($trueKey, $defs)) {
                $newSeq[4][1] = TokenUtil::encapsulate($defs[$trueKey]);
            }
            return $newSeq;
        });
        $newTokens = $representation->getTokens();
        return Tokens::toFile($newTokens, $dstFile);
    }


    /**
     * - translationIds: an array of translation ids
     * - removeObsoleteFromSrc:
     *          use this mode if you trust the Linguist mechanism for scanning translations from files,
     *          which should be reliable for most cases
     */
    public static function complete($srcFile, array $translationIds = [], $removeObsoleteFromSrc = true)
    {
        if (file_exists($srcFile)) {
            $defs = [];
            require $srcFile;
            $newDefs = $defs;
            foreach ($translationIds as $id) {
                if (false === array_key_exists($id, $defs)) {
                    $newDefs[$id] = $id;
                }
            }
            if (true === $removeObsoleteFromSrc) {
                foreach ($newDefs as $k => $v) {
                    if (!in_array($k, $translationIds)) {
                        unset($newDefs[$k]);
                    }
                }
            }
            $content = TranslationFileTemplate::getContentByDefs($newDefs);
            file_put_contents($srcFile, $content);
        }
    }


    private static function getMessageLineTokenRepresentation($tokenIdentifiers, array $defs)
    {
        $representation = TokenRepresentation::create($tokenIdentifiers);

        return $representation
            ->addReplacementSequence(
                ReplacementSequence::create()
                    ->addToken(
                        ReplacementSequenceToken::create()
                            ->matchIf(function (&$tokenIdentifier) {
                                return (is_array($tokenIdentifier) && T_CONSTANT_ENCAPSED_STRING === $tokenIdentifier[0]);
                            })
                    )
                    ->addToken(
                        ReplacementSequenceToken::create()
                            ->optional()
                            ->matchIf(function ($tokenIdentifier) {
                                return (is_array($tokenIdentifier) && T_WHITESPACE === $tokenIdentifier[0]);
                            })
                    )
                    ->addToken(
                        ReplacementSequenceToken::create()
                            ->matchIf(function ($tokenIdentifier) {
                                return (is_array($tokenIdentifier) && T_DOUBLE_ARROW === $tokenIdentifier[0]);
                            })
                    )
                    ->addToken(
                        ReplacementSequenceToken::create()
                            ->optional()
                            ->matchIf(function ($tokenIdentifier) {
                                return (is_array($tokenIdentifier) && T_WHITESPACE === $tokenIdentifier[0]);
                            })
                    )
                    ->addToken(
                        ReplacementSequenceToken::create()
                            ->matchIf(function (&$tokenIdentifier) use ($defs) {
                                return (is_array($tokenIdentifier) && T_CONSTANT_ENCAPSED_STRING === $tokenIdentifier[0]);
                            })
                    )
            );
    }
}