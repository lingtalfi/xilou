<?php


namespace Stat\Analyzer;


class DayRangeIterator
{


    /**
     * Go through all the possible days (yyyy-mm-dd format) between the given start and end date. and provides
     * you the opportunity to do something with the date via the callback.
     *
     * It does not try to use real days, but rather use 31 days per month (i.e. you need to check that the day
     * actually exist before processing it).
     *
     *
     * @param $startDate YYYY-mm-dd
     * @param $endDate YYYY-mm-dd
     *
     * endDate > startDate
     *
     *
     */
    public static function iterate($startDate, $endDate, $func, $limit = null)
    {
        $p = explode('-', $startDate);
        if (null === $limit) {
            $limit = 1000000;
        }

        list($startYear, $startMonth, $startDay) = $p;

        $startYear = (int)$startYear;
        $startMonth = (int)$startMonth;
        $startDay = (int)$startDay;

        $day = $startDay;
        $month = $startMonth;
        $year = $startYear;

        $c = 0;
        while (true) {
            $c++;
            if ($c > $limit) {
                break;
            }

            $date = "$year-" . sprintf('%02s', $month) . '-' . sprintf('%02s', $day);
            call_user_func($func, $date);
            if ($endDate === $date) {
                break;
            }

            $day++;
            if (32 === $day) {
                $day = 1;
                $month++;
                if (13 === $month) {
                    $month = 1;
                    $year++;
                }
            }
        }
    }
}