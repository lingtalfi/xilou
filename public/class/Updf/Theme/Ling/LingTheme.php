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
        $this->setMultiple([
            'theme_logo' => $logoSrc,
            'theme_logo_width' => 100,
            'theme_cellpadding' => 4,
            'theme_bg_color' => "#ccc",
            'theme_font_size' => "9px",
            // table
            'theme_table_border_color' => "#ccc",
            'theme_th_bg_color' => "#eee",
            'theme_th_font_size' => "8px",
            'theme_td_font_size' => "7px",
        ]);
    }

}