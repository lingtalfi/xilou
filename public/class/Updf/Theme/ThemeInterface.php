<?php


namespace Updf\Theme;


interface ThemeInterface
{

    public function get($name);

    public function getAll();

    public function set($name, $value);

    public function setLang($lang);

    public function getLang();

}