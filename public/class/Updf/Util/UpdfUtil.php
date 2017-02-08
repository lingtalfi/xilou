<?php


namespace Updf\Util;


class UpdfUtil
{


    /**
     * @return string, the string to use inside an html <img> tag's src attribute
     */
    public static function getImgSrc($path)
    {
        return 'data:image/$ext;base64,' . base64_encode(file_get_contents($path));
    }
}