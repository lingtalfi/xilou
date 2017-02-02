<?php


namespace Http;

use Bat\UriTool;

class HttpResponseUtil
{
    public static function redirect($url)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        $url = UriTool::getWebsiteAbsoluteUrl() . $url;
        header("Location: " . $url);
        exit;
    }
}