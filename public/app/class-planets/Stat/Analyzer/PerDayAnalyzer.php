<?php


namespace Stat\Analyzer;


use Stat\Analyzer\Cache\PerDayAnalyzerCacheInterface;
use Stat\Extractor\ExtractorInterface;

class PerDayAnalyzer
{

    private $_suffix;
    /**
     * @var PerDayAnalyzerCacheInterface
     */
    private $cache;

    public function __construct()
    {
        $this->_suffix = '';
    }

    public function setCache(PerDayAnalyzerCacheInterface $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @param $startDay YYYY-mm-dd
     * @param $endDay YYYY-mm-dd
     */
    public function analyze($startDay, $endDay, $dir, ExtractorInterface $extractor)
    {
        $ret = [];
        if ($endDay >= $startDay) {

            if (null !== $this->cache) {
                if (false !== ($data = $this->cache->getPeriod($startDay, $endDay))) {
                    return $data;
                }
            }


            DayRangeIterator::iterate($startDay, $endDay, function ($date) use ($dir, $extractor, &$ret) {


                $f = PerDayAnalyzerHelper::getDayPath($dir, $date, $this->_suffix);
                if (file_exists($f)) {
                    if (null !== $this->cache) {
                        $data = $this->cache->getDay($date);
                        if (false === $data) {
                            $data = $extractor->getData($f);
                            $this->cache->storeDay($date, $data);
                        }
                    } else {
                        $data = $extractor->getData($f);
                    }


                    $this->combineData($data, $ret, $date);
                }


            });
            if (count($ret) > 0) {
                if (null !== $this->cache) {
                    $this->cache->storePeriod($startDay, $endDay, $ret);
                }
            }


        } else {
            throw new \Exception("endDate ($endDay) must be after startDate ($startDay)");
        }
        return $ret;
    }

    public function suffix($suffix)
    {
        $this->_suffix = $suffix;
        return $this;
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    protected function combineData(array $data, array &$ret, $date)
    {
        foreach ($data as $k => $v) {
            $v = (int)$v;
            if (array_key_exists($k, $ret)) {
                $ret[$k] += $v;
            } else {
                $ret[$k] = $v;
            }
        }
    }
}