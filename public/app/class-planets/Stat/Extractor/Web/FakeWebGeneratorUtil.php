<?php


namespace Stat\Extractor\Web;


use Bat\FileSystemTool;
use Stat\Analyzer\DayRangeIterator;
use Stat\Analyzer\PerDayAnalyzerHelper;

class FakeWebGeneratorUtil
{

    /**
     * Those are files that contain one entry per line.
     * The langFile contains the HTTP_ACCEPT_LANGUAGE strings.
     * The webFile contains the HTTP_USER_AGENT strings.
     */
    private $langFile;
    private $webFile;

    /**
     * base number is the number of times a pair of lang/agent lines are added to the generated file.
     * max variance is the maximum variance from that base number. The variance is applied randomly, and can
     * be applied both ways (negative).
     */
    private $_baseNumber;
    private $_maxVariance;


    public function __construct()
    {
        $this->langFile = __DIR__ . "/Assets/HTTP_ACCEPT_LANGUAGE.txt";
        $this->webFile = __DIR__ . "/Assets/HTTP_USER_AGENT.txt";
        $this->_baseNumber = 50;
        $this->_maxVariance = 10;
    }

    public function baseNumber($n)
    {
        $this->_baseNumber = $n;
        return $this;
    }

    public function maxVariance($n)
    {
        $this->_maxVariance = $n;
        return $this;
    }

    public function generateByPeriod($dir, $startDay, $endDay)
    {
        if (is_dir($dir)) {
            if ($startDay < $endDay) {


                $langs = file($this->langFile);
                $agents = file($this->webFile);

                $langs = array_filter($langs);
                $agents = array_filter($agents);
                $maxLang = count($langs) - 1;
                $maxAgent = count($agents) - 1;

                $baseNumber = $this->_baseNumber;


                DayRangeIterator::iterate($startDay, $endDay, function ($date) use (
                    $dir, $langs, $agents,
                    $maxAgent, $maxLang, $baseNumber
                ) {
                    $file = PerDayAnalyzerHelper::getDayPath($dir, $date);
                    $variance = rand(0, $this->_maxVariance);

                    if (0 === rand(0, 1)) {
                        $baseNumber -= $variance;
                    } else {
                        $baseNumber += $variance;
                    }
                    if ($baseNumber < 1) {
                        $baseNumber = 1;
                    }
                    $content = '';
                    for ($i = 0; $i <= $baseNumber; $i++) {
                        $lang = trim($langs[rand(0, $maxLang)]);
                        $agent = trim($agents[rand(0, $maxAgent)]);
                        $content .= '-' . $lang . PHP_EOL . '+' . $agent . PHP_EOL;
                    }
                    FileSystemTool::mkfile($file, $content);

                });
            } else {
                throw new \Exception("startDay ($startDay) must be BEFORE endDay ($endDay)");
            }
        } else {
            throw new \Exception("Dir not found: $dir");
        }
    }


}