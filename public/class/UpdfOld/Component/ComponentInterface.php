<?php


namespace Updf\Component;


use Updf\Theme\ThemeInterface;

interface ComponentInterface
{
    public function getTemplateName();

    public function getTemplateVars();

    public function setTheme(ThemeInterface $theme);
}


