<?php


namespace Stat\Analyzer\Cache;


interface PerDayAnalyzerCacheInterface
{

    public function storeDay($day, array $data);

    /**
     * @param $day
     * @return false|array
     */
    public function getDay($day);



    public function storePeriod($startDay, $endDay, array $data);

    /**
     * @param $startDay
     * @param $endDay
     * @return false|array
     */
    public function getPeriod($startDay, $endDay);
}