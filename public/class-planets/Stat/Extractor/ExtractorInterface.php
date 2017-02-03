<?php


namespace Stat\Extractor;


interface ExtractorInterface
{

    /**
     * @return array,
     *      - containing the data for the given file
     *      - the data is a summary of available type of data (note: it's not suited for individual criteria like IP address),
     *          but only criteria that are somehow factorizable, for instance:
     *
     *              - lang_en
     *              - lang_fr
     *              - browser_1
     *              - browser_2
     *
     *          or
     *
     *              - count
     *
     *
     *
     */
    public function getData($file);
}