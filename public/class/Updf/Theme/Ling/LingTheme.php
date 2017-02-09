<?php


namespace Updf\Theme\Ling;

use Updf\Theme\Theme;

class LingTheme extends Theme
{


    public function __construct()
    {


        parent::__construct();


        $logoPath = __DIR__ . '/assets/updf-default-logo.png';
        $logoSrc = 'data:image/$ext;base64,' . base64_encode(file_get_contents($logoPath));
        $this->set('theme_logo', $logoSrc);
        $this->set('theme_logo_width', 100);
        $this->set('theme_cellpadding', 4);
    }

}