<?php

namespace Shared\FrontOne;


class FrontOneConfig
{


    public static function getThemePage()
    {
        return "modules/frontOne/frontOne-theme.php";
    }

    public static function getThemeUri()
    {
        return "/front-one/theme";
    }

    public static function getArticlesPage()
    {
        return "modules/frontOne/frontOne-articles.php";
    }

    public static function getArticlesUri()
    {
        return "/front-one/articles";
    }

    public static function getSocialPage()
    {
        return "modules/frontOne/frontOne-social.php";
    }

    public static function getSocialUri()
    {
        return "/front-one/social-links";
    }

    public static function getThemeFile()
    {
        return Ether::get('BACK_ROOT_DIR') . '/assets/modules/frontOne/theme.php';
    }

    public static function getDefaultTheme()
    {
        return [
            'seoTitle' => 'Dimension by HTML5 UP',
            'title' => 'Dimension',
            'headerParagraph' => 'A fully responsive site template designed by <a href="https://html5up.net">HTML5
                                UP</a> and
                            released<!--]--><br/>
                            <!--[-->for free under the <a href="https://html5up.net/license">Creative Commons</a>
                            license.',
            'footerParagraph' => '&copy; Untitled. Design: <a href="https://html5up.net">HTML5 UP</a>.',
            'icon' => 'fa-diamond', // http://fontawesome.io/icons/
        ];
    }


    public static function getSocialLinksFile()
    {
        return Ether::get('BACK_ROOT_DIR') . '/assets/modules/frontOne/social.php';
    }

    public static function getDefaultSocialLinks()
    {
        return [
            'facebook' => '',
            'github' => '',
            'instagram' => '',
            'twitter' => '',
        ];
    }

}