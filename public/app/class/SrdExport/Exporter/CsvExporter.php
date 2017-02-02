<?php


namespace SrdExport\Exporter;


use SrdExport\Exporter\Exception\ExporterException;

class CsvExporter
{


    private $settings;
    private $fields;

    public function __construct()
    {
        $this->settings = [];
        $this->fields = [];
    }


    /**
     *
     * array of key => [type, lg, dec]
     *                  - type: C|D|I|L|N
     *                      - C: char
     *                      - D: date
     *                      - I: integer
     *                      - L: list? voir avec srd
     *                      - N: nullable? voir avec srd
     *                  - ?lg: int, only if type=C
     *                  - ?dec: int, only if type=C
     *
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        return $this;
    }

//    public function setFields(array $fields)
//    {
//        $this->fields = $fields;
//        return $this;
//    }

    public function addFields(array $fields)
    {
        $this->fields[] = $fields;
        return $this;
    }


    public function export($dstFile)
    {

        $formattedData = [];

        //------------------------------------------------------------------------------/
        // formatting data
        //------------------------------------------------------------------------------/
        foreach ($this->fields as $m => $fields) {
            $item = [];
            foreach ($fields as $k => $v) {
                if (array_key_exists($k, $this->settings)) {
                    list($type, $length, $decimal) = $this->settings[$k];

                    if ('C' === $type || 'I' === $type || 'N' === $type) {
                        if ('N' === $type && $decimal > 0) {
                            $v = number_format($v, $decimal);
                        }
                        $v = str_pad($v, $length, ' ', STR_PAD_RIGHT);
                        if (strlen($v) > $length) {
                            $v = substr($v, 0, $length);
                        }
                    } elseif ('D' === $type) {
                        if (strlen($v) !== 10) {
                            $v = '0000-00-00';
                        }
                    }

                    $item[$k] = $v;
                    $formattedData[$m] = $item;
                } else {
                    throw new ExporterException("Field not found: $k");
                }
            }
        }

//        a($formattedData);
//        return;


        //------------------------------------------------------------------------------/
        // write the csv file
        //------------------------------------------------------------------------------/
        $fp = fopen($dstFile, 'w');
        foreach ($formattedData as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }


}