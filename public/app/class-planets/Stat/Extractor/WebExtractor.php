<?php


namespace Stat\Extractor;


use Stat\Extractor\Web\BrowserUtil;


class WebExtractor implements ExtractorInterface
{

    public function getData($file)
    {
        $ret = [];
        $content = file_get_contents($file);

        $this->extractLangs($content, $ret);

        $o = new BrowserUtil();


        if (preg_match_all('!^\+(.*)!mi', $content, $matches)) {
            foreach ($matches[1] as $userAgent) {

                $o->reset();
                $o->setUserAgent($userAgent);


                $browser = $o->getBrowser();
                $platform = $o->getPlatform();
                $browserVersion = $o->getVersion();


                if (array_key_exists('browser:' . $browser, $ret)) {
                    $ret['browser:' . $browser]++;
                } else {
                    $ret['browser:' . $browser] = 1;
                }
                if (array_key_exists('browserVersion:' . $browserVersion, $ret)) {
                    $ret['browserVersion:' . $browserVersion]++;
                } else {
                    $ret['browserVersion:' . $browserVersion] = 1;
                }
                if (array_key_exists('platform:' . $platform, $ret)) {
                    $ret['platform:' . $platform]++;
                } else {
                    $ret['platform:' . $platform] = 1;
                }
            }
        }
        return $ret;
    }

    private function extractLangs($content, array &$ret)
    {
        if (preg_match_all('!^-([^,]*)!m', $content, $matches)) {
            foreach ($matches[1] as $lang) {
                if (array_key_exists('lang:' . $lang, $ret)) {
                    $ret['lang:' . $lang]++;
                } else {
                    $ret['lang:' . $lang] = 1;
                }
            }
        }
    }
}