<?php

namespace QuickDoc;


class QuickDocConfig
{

    public static function getPage()
    {
        return "modules/quickDoc/quickDoc.php";
    }

    public static function getUri()
    {
        return "/quickdoc";
    }

    public static function getAllowedMappings()
    {
        return ['links', 'images'];
    }


    public static function getMappingsDir()
    {
        return APP_ROOT_DIR . "/assets/modules/quickDoc";
    }




}