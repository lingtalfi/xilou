<?php


namespace Stat\Analyzer;


class CumulatorPerDayAnalyzer extends PerDayAnalyzer
{

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    protected function combineData(array $data, array &$ret, $date)
    {
        $ret[$date] = $data['count'];
    }
}