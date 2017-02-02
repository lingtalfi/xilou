<?php


namespace Stat\Extractor;


class CounterExtractor implements ExtractorInterface
{

    public function getData($file)
    {
        return [
            "count" => filesize($file),
        ];
    }
}