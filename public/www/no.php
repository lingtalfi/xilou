<?php


use Csv\CsvUtil;
use QuickPdo\QuickPdo;
use Util\ArrayRenderer;
use Util\RowsRenderer;

require_once __DIR__ . "/../init.php";




$f = "/Users/lafitte/Downloads/COMMANDE ZILU 02-2017.xlsx";
$f = "/Users/pierrelafitte/Downloads/COMMANDE ZILU 02-2017.xlsx";


/** Include PHPExcel_IOFactory */
require_once __DIR__ . '/PHPExcel/Classes/PHPExcel/IOFactory.php';


function createCommandeByCsv($commandeName, $csvFile)
{
    $o = PHPExcel_IOFactory::load($csvFile);
    $sheet = $o->getActiveSheet();
    $cit = $sheet->getColumnIterator('A', 'A');
    foreach ($cit as $col) {
        $it = $col->getCellIterator();
        foreach ($it as $cell) {
            /**
             * @var PHPExcel_Cell $cell
             */
            $val = $cell->getValue();
            if ('REFART' !== $val && null !== $val) {
                $val = (string)$val;

            }
        }
    }
}


//createCommandeByCsv("C_test", $f);

function renderCsv($f)
{
    $o = PHPExcel_IOFactory::load($f);
    $sheet = $o->getActiveSheet();
    $a = $sheet->toArray();
    RowsRenderer::create()->setValues($a)->render();
}
renderCsv($f);