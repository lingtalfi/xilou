<?php

namespace Shared\FrontOne;


class FileArticle extends Article
{

    private $file;

    public function setFile($file)
    {
        $this->file = $file;
        $this->setContent(file_get_contents($file));
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }


}


