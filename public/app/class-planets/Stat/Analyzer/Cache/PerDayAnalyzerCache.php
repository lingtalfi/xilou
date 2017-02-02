<?php


namespace Stat\Analyzer\Cache;


use Bat\FileSystemTool;
use Stat\Analyzer\PerDayAnalyzerHelper;

class PerDayAnalyzerCache implements PerDayAnalyzerCacheInterface
{

    private $dir;

    public function __construct($dir)
    {
        $this->dir = $dir;
        if (!file_exists($dir)) {
            throw new \Exception("The directory does not exist: $dir");
        }
        $dayDir = $this->dir . "/days";
        $periodDir = $this->dir . "/periods";
        if (!file_exists($dayDir)) {
            mkdir($dayDir);
        }
        if (!file_exists($periodDir)) {
            mkdir($periodDir);
        }
    }

    public function storeDay($day, array $data)
    {
        if ($day === date('Y-m-d')) {
            return false; // don't store the current day
        }
        $data = serialize($data);
        $file = PerDayAnalyzerHelper::getDayPath($this->dir, $day);
        FileSystemTool::mkfile($file, $data);
    }

    public function getDay($day)
    {
        $file = PerDayAnalyzerHelper::getDayPath($this->dir, $day);
        if (file_exists($file)) {
            $c = file_get_contents($file);
            return unserialize($c);
        }
        return false;
    }

    public function storePeriod($startDay, $endDay, array $data)
    {
        $currentDay = date('Y-m-d');
        if ($currentDay >= $startDay && $currentDay <= $endDay) {
            return false; // don't store a period which contains the current day
        }
        $data = serialize($data);
        $file = $this->dir . "/periods/$startDay--$endDay.txt";
        file_put_contents($file, $data);
    }

    public function getPeriod($startDay, $endDay)
    {
        $file = $this->dir . "/periods/$startDay--$endDay.txt";
        if (file_exists($file)) {
            $c = file_get_contents($file);
            return unserialize($c);
        }
        return false;
    }


}