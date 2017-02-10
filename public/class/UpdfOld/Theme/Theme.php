<?php


namespace Updf\Theme;


class Theme implements ThemeInterface
{

    private $vars;
    private $lang;

    public function __construct()
    {
        $this->vars = [];
        $this->lang = 'en';
    }


    public function get($name)
    {
        if (array_key_exists($name, $this->vars)) {
            return $this->vars[$name];
        }
        return '';
    }

    public function set($name, $value)
    {
        $this->vars[$name] = $value;
        return $this;
    }

    public function setMultiple(array $values)
    {
        foreach ($values as $k => $v) {
            $this->vars[$k] = $v;
        }
        return $this;
    }

    public function getAll()
    {
        return $this->vars;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function getLang()
    {
        return $this->lang;
    }


}