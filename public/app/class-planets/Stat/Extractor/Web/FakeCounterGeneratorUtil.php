<?php


namespace Stat\Extractor\Web;


use Bat\FileSystemTool;
use Stat\Analyzer\DayRangeIterator;
use Stat\Analyzer\PerDayAnalyzerHelper;

class FakeCounterGeneratorUtil
{
    /**
     * base number is the number of visits
     * max variance is the maximum variance from that base number. The variance is applied randomly, and can
     * be applied both ways (negative).
     */
    private $_baseNumber;
    private $_maxVariance;


    public function __construct()
    {
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

                $baseNumber = $this->_baseNumber;


                DayRangeIterator::iterate($startDay, $endDay, function ($date) use (
                    $dir, $baseNumber
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
                    $content = str_repeat('-', $baseNumber);
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