<?php


namespace FileCleaner;


use FileKeeper\FileKeeperInterface;

/**
 * Use this class to clean a directory.
 * There are three phases:
 *
 * - scan
 * - collect
 * - delete
 *
 *
 * To the cleaner we bind some keeper instances.
 * Basically, the synopsis is the following:
 *
 * The cleaner parses each file in the target directory, one by one.
 *
 * The keepers act as listeners, and are notified of every file parsed.
 * This is the opportunity for them to create lists of files to keep.
 * This is called the scan phase.
 *
 * Then the cleaner ask every keeper for their keep list, and mix them together
 * in a big list of files to keep.
 *
 * Finally, once this big list is created, the cleaner, on its own,
 * re-parse the directory and delete all the files, except for those in the keep list.
 *
 *
 *
 *
 */
class FileCleaner
{

    private $_dir;
    private $keepCallbacks;
    private $filesToKeep;
    private $keepers;


    public function __construct()
    {
        $this->_dir = null;
        $this->keepCallbacks = [];
        $this->filesToKeep = [];
        $this->keepers = [];
    }

    public static function create()
    {
        return new static();
    }


    public function keep($howMany, $mode = 'oldest')
    {
        return $this;
    }

    public function clean()
    {
        if (is_dir($this->_dir)) {

            $allFiles = [];
            $filesToKeep = [];


            $this->prepare();
            $this->scan($this->_dir, $allFiles);
            $this->collect($filesToKeep);
            $this->cleanDir($allFiles, $filesToKeep);


        } else {
            $this->error("dir is not a directory: " . $this->_dir);
        }
    }


    public function setDir($dir)
    {
        $this->_dir = $dir;
        return $this;
    }


    public function addKeeper(FileKeeperInterface $keeper)
    {
        $this->keepers[] = $keeper;
        return $this;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected function error($msg)
    {
        throw new \Exception($msg);
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    private function prepare()
    {
        foreach ($this->keepers as $k) {
            $k->setDir($this->_dir);
        }
    }

    private function scan($dir, array &$allFiles)
    {
        $files = scandir($dir);
        foreach ($files as $f) {
            if ('.' !== $f && '..' !== $f) {
                $file = $dir . "/" . $f;
                if (is_dir($file)) {
                    $this->scan($file, $allFiles);
                } else {
                    $allFiles[] = $f;
                    foreach ($this->keepers as $keeper) {
                        $keeper->listen($f, $file);
                    }
                }
            }
        }
    }

    private function collect(array &$filesToKeep)
    {
        foreach ($this->keepers as $k) {
            $filesToKeep = array_merge($filesToKeep, $k->getKeptFiles());
        }
    }

    private function cleanDir(array $allFiles, array $filesToKeep)
    {
        $files = array_diff($allFiles, $filesToKeep);
        foreach ($files as $file) {
            a("unlinking: " . $file);
        }
    }

}