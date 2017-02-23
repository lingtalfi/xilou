<?php


namespace FileCleaner;


class FileCleaner
{

    private $_startDate;
    private $_dir;
    private $keepCallbacks;
    private $filesToKeep;


    public function __construct()
    {
        $this->_dir = null;
        $this->_startDate = null;
        $this->keepCallbacks = [];
        $this->filesToKeep = [];
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
            $this->collectFiles($this->_dir, $allFiles);

            $this->filesToKeep = [];
            $this->scanDir($this->_dir, $allFiles);

            $this->cleanDir($this->_dir, $this->filesToKeep);

        } else {
            $this->error("dir is not a directory: " . $this->_dir);
        }
    }


    public function setStartDate($startDate)
    {
        $this->_startDate = $startDate;
        return $this;
    }

    public function setDir($dir)
    {
        $this->_dir = $dir;
        return $this;
    }

    /**
     * @param array $keepCallbacks
     */
    public function setKeepCallback(\Closure $fn)
    {
        $this->keepCallbacks[] = $fn;
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
    private function collectFiles($dir, array &$allFiles)
    {
        $files = scandir($dir);
        foreach ($files as $f) {
            if ('.' !== $f && '..' !== $f) {
                $file = $dir . "/" . $f;
                if (is_dir($file)) {
                    $this->collectFiles($file, $allFiles);
                } else {
                    $allFiles[] = $f;
                }
            }
        }
    }

    private function scanDir($dir, array $allFiles)
    {
        $files = scandir($dir);
        foreach ($files as $f) {
            $file = $dir . "/" . $f;
            if (is_dir($file)) {
                $this->scanDir($file, $allFiles);
            } else {
                foreach ($this->keepCallbacks as $fn) {
                    if (true === call_user_func($fn, $f, $this->_startDate, $file, $allFiles)) {
                        $this->filesToKeep[] = $file;
                    }
                }
            }
        }
    }

    private function cleanDir($dir, array $filesToKeep)
    {
        $files = scandir($dir);
        foreach ($files as $f) {
            $file = $dir . "/" . $f;
            if (is_dir($file)) {
                $this->cleanDir($file, $filesToKeep);
            } else {
                if (false === in_array($file, $filesToKeep)) {
                    a("unlinking: " . $file);
//                    unlink($file);
                }
            }
        }
    }

}