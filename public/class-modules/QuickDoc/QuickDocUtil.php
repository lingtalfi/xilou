<?php


namespace QuickDoc;


use ArrayExport\ArrayExport;
use ArrayStore\ArrayStore;
use Bat\FileSystemTool;
use DirTransformer\Scanner\Scanner;
use DirTransformer\Transformer\TrackingMapRegexTransformer;
use QuickDoc\DirTransformer\Transformer\TocTransformer;
use QuickDoc\DirTransformer\Transformer\TreeTransformer;

class QuickDocUtil
{

    private static $noSrcDirErrMsg = "The source dir or destination dir is not set. Please update your configuration first.";
    private static $linksStore;
    private static $imagesStore;

    //--------------------------------------------
    // Locations
    public static function getTabUri($tab)
    {
        return QuickDocConfig::getUri() . "?tab=" . $tab;
    }

    public static function getMappingsFile($name)
    {
        if (in_array($name, QuickDocConfig::getAllowedMappings(), true)) {
            return QuickDocConfig::getMappingsDir() . "/$name.php";
        }
        throw new \Exception("This mapping is not allowed: $name");
    }



    //--------------------------------------------
    // Mappings
    public static function getMappings($name)
    {
        $ret = self::getStoreByName($name)->retrieve();
        if (0 === count($ret)) {
            $ret = [
                'found' => [],
                'unfound' => [],
            ];
        }
        return $ret;
    }

    public static function setMappings($name, array $mappings)
    {
        return self::getStoreByName($name)->store($mappings);
    }

    public static function mergeMappings($name, array $mappings)
    {
        $actualMappings = self::getMappings($name);
        $actualFound = $actualMappings['found'];
        $actualUnfound = $actualMappings['unfound'];
        $found = $mappings['found'];
        $unfound = $mappings['unfound'];


        $removeFromUnfound = [];
        foreach ($found as $file => $maps) {
            if (false === array_key_exists($file, $actualFound)) {
                $actualFound[$file] = $maps;
                $removeFromUnfound[$file] = $maps;
            } else {
                foreach ($maps as $map) {
                    $label = $map[0];
                    $alreadyExist = false;
                    foreach ($actualFound[$file] as $_map) {
                        if ($label === $_map[0]) {
                            $alreadyExist = true;
                            break;
                        }
                    }
                    if (false === $alreadyExist) {
                        $actualFound[$file][] = $map;
                        $removeFromUnfound[$file][] = $map;
                    }
                }
            }
        }

        foreach ($removeFromUnfound as $file => $maps) {
            foreach ($maps as $map) {
                $label = $map[0];
                if (array_key_exists($file, $actualUnfound)) {
                    $_maps = $actualUnfound[$file];
                    foreach ($_maps as $k => $_map) {
                        if ($label === $_map[0]) {
                            unset($_maps[$k]);
                        }
                    }
                    if (0 === count($_maps)) {
                        unset($actualUnfound[$file]);
                    } else {
                        $actualUnfound[$file] = $_maps;
                    }
                }
            }
        }

        $mappings = [
            'found' => $actualFound,
            'unfound' => $actualUnfound,
        ];

        return self::getStoreByName($name)->store($mappings);
    }

    public static function countUnfoundItemsByName($name)
    {
        $n = 0;
        $mappings = self::getMappings($name);
        $found = $mappings['unfound'];
        foreach ($found as $items) {
            $n += count($items);
        }
        return $n;
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    public static function copyDoc()
    {
        return self::_copyDoc(false);
    }


    public static function scanDoc()
    {
        return self::_copyDoc(true);
    }


    //--------------------------------------------
    //
    //--------------------------------------------

    private static function _copyDoc($dry)
    {
        $prefs = QuickDocPreferences::getPreferences();
        $srcDir = $prefs['srcDir'];
        $dstDir = $prefs['dstDir'];
        if (null === $srcDir || null === $dstDir) {
            throw new QuickDocException(__(self::$noSrcDirErrMsg, "modules/quickDoc/quickDoc"));
        }


        $linksTransformer = self::getTransformerByName("links");
        $imagesTransformer = self::getTransformerByName("images");

        $scanner = Scanner::create();
        if (true === $dry) {
            $scanner->dryRun();
        }

        $scanner->allowedExtensions(['md'])
            ->addTransformer($linksTransformer)
            ->addTransformer($imagesTransformer)
            ->addTransformer(new TocTransformer())
            ->addTransformer(new TreeTransformer($srcDir, $prefs['linksUrlPrefix']))
            ->copy($srcDir, $dstDir);


        // links
        $found = self::cleanFound($linksTransformer->getFoundList());
        $unfound = self::cleanUnfound($linksTransformer->getUnfoundList());
        $mappings = [
            'found' => $found,
            'unfound' => $unfound,
        ];
        $mappings = self::filterMappings($mappings);
        self::setMappings("links", $mappings);


        // images
        $found = self::cleanFound($imagesTransformer->getFoundList());
        $unfound = self::cleanUnfound($imagesTransformer->getUnfoundList());
        $mappings = [
            'found' => $found,
            'unfound' => $unfound,
        ];
        $mappings = self::filterMappings($mappings);
        self::setMappings("images", $mappings);


        return true;
    }


    private static function cleanFound(array $found)
    {
        $ret = [];
        foreach ($found as $file => $items) {
            foreach ($items as $item) {
                $ret[$file][] = [
                    $item[1],
                    $item[2],
                ];
            }
        }
        return $ret;
    }

    private static function cleanUnfound(array $found)
    {
        $ret = [];
        foreach ($found as $file => $items) {
            foreach ($items as $item) {
                $ret[$file][] = [
                    $item[1],
                ];
            }
        }
        return $ret;
    }


    /**
     * @returns ArrayStore
     */
    private static function getStoreByName($name)
    {
        $store = null;
        if ('links' === $name) {
            $store = QuickDocUtil::getLinksStore();
        } elseif ('images' === $name) {
            $store = QuickDocUtil::getImagesStore();
        } else {
            throw new \Exception("No store bound to the name $name");
        }
        return $store;
    }


    /**
     * @returns TrackingMapRegexTransformer
     */
    private static function getTransformerByName($name)
    {

        $prefs = QuickDocPreferences::getPreferences();
        $srcDir = $prefs['srcDir'];
        if (file_exists($srcDir)) {

            $srcDir = rtrim($srcDir, '/');
            $len = strlen($srcDir) + 1;


            $transformer = null;
            if ('links' === $name) {


                $linksUrlPrefix = $prefs['linksUrlPrefix'];
                $linksAbsoluteUrlPrefix = $prefs['linksAbsoluteUrlPrefix'];

                $transformer = TrackingMapRegexTransformer::create()
                    ->regex('/<-\s*(.*)\s*->/U')
                    ->map(self::getFoundMapByName($name))
                    ->fileFunc(function ($file) use ($srcDir, $len) {
                        if (0 === strpos($file, $srcDir)) {
                            $file = '/' . substr($file, $len);
                        }
                        return $file;
                    })
                    ->onFound(function ($match, $value) use ($linksUrlPrefix, $linksAbsoluteUrlPrefix) {
                        $prefix = $linksUrlPrefix;
                        if ('/' === substr($value, 0, 1)) {
                            $prefix = $linksAbsoluteUrlPrefix;
                        }
                        return '[' . $match . '](' . $prefix . $value . ')';
                    });;
            } elseif ('images' === $name) {
                $transformer = TrackingMapRegexTransformer::create()
                    ->regex('/<!-\s*(.*)\s*->/U')
                    ->map(self::getFoundMapByName($name))
                    ->fileFunc(function ($file) use ($srcDir, $len) {
                        if (0 === strpos($file, $srcDir)) {
                            $file = '/' . substr($file, $len);
                        }
                        return $file;
                    })
                    ->onFound(function ($match, $value) {
                        return $value; // actually, I found it easier to return the whole value, your mileage may vary...
//                        return '![' . $match . '](' . $value . ')';
                    });
            } else {
                throw new \Exception("No store bound to the name $name");
            }
            return $transformer;
        }
        throw new \Exception("The srcDir does not exist");
    }

    private static function getFoundMapByName($name)
    {
        $map = [];
        $mappings = self::getMappings($name);
        $found = $mappings['found'];
        foreach ($found as $file => $items) {
            foreach ($items as $item) {
                $map[$item[0]] = $item[1];
            }
        }
        return $map;
    }


    private static function filterMappings(array $mappings)
    {
        $ret = [];
        $found = $mappings['found'];
        $unfound = $mappings['unfound'];
        $newFound = [];
        $newUnfound = [];

        $cache = [];
        foreach ($found as $file => $maps) {
            foreach ($maps as $k => $map) {
                $id = $map[0];
                if (false === array_key_exists($id, $cache)) {
                    $cache[$id] = true;
                    $newFound[$file][$k] = $map;
                }
            }
        }


        $cache = [];
        foreach ($unfound as $file => $maps) {
            foreach ($maps as $k => $map) {
                $id = $map[0];
                if (false === array_key_exists($id, $cache)) {
                    $cache[$id] = true;
                    $newUnfound[$file][$k] = $map;
                }
            }
        }

        $ret = [
            'found' => $newFound,
            'unfound' => $newUnfound,
        ];
        return $ret;
    }


    //------------------------------------------------------------------------------/
    // ARRAY STORES
    //------------------------------------------------------------------------------/
    /**
     * @return ArrayStore
     */
    public static function getLinksStore()
    {
        if (null === self::$linksStore) {
            self::$linksStore = ArrayStore::create()->path(QuickDocUtil::getMappingsFile('links'));
        }
        return self::$linksStore;
    }


    /**
     * @return ArrayStore
     */
    public static function getImagesStore()
    {
        if (null === self::$imagesStore) {
            self::$imagesStore = ArrayStore::create()->path(QuickDocUtil::getMappingsFile('images'));
        }
        return self::$imagesStore;
    }

}