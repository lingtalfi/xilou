<?php


namespace CsvExport;


use QuickPdo\QuickPdo;

class CommandeExporterUtil
{


    public static function createCsvFileByCommande($file, $commandeId)
    {
        self::start();


        $boldArray = [
            'font' => [
                'bold' => true,
            ],
        ];

        $query = "select

a.reference_lf as refart,
co.nom as container_nom,
f.nom as fournisseur_nom,
a.descr_fr,
fha.prix,
h.prix_override,
h.quantite,
h.date_estimee,
h.unit


from zilu.commande c
inner join commande_has_article h on h.commande_id=c.id
inner join fournisseur f on f.id=h.fournisseur_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
inner join article a on a.id=h.article_id
left join container co on co.id=h.container_id
where c.id=" . $commandeId;


        $quantiteTotale = 0;
        $prixTotal = 0;

        $items = QuickPdo::fetchAll($query);
        $csvItems = [];
        foreach ($items as $item) {
            $prix = $item['prix'];
            if (null !== $item['prix_override']) {
                $prix = $item['prix_override'];
            }
            $total = $item['quantite'] * $prix;
            $quantiteTotale += $item['quantite'];
            $prixTotal += $total;


            $csvItems[] = [
                $item['refart'],
                $item['fournisseur_nom'],
                $item['descr_fr'],
                '$' . $prix,
                $item['quantite'],
                $item['unit'],
                $item['container_nom'],
                $item['date_estimee'],
                '$' . $total,
            ];
        }


        $headers = [
            "REFART",
            "NOM",
            "DESIGN",
            "PRIX",
            "QUANTITÉ",
            "PC/PR",
            "CONTAINER",
            "DATE ESTIMÉE",
            "TOTAL",
        ];

        $objPHPExcel = new \PHPExcel();
        $ws = $objPHPExcel->setActiveSheetIndex(0);
        $i = 0;

        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($boldArray);


//        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray([
//            ''
//        ]);


        // $objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($boldArray);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);


        self::arrayToCells($headers, $csvItems, $ws, $i);


        //------------------------------------------------------------------------------/
        //
        //------------------------------------------------------------------------------/
        $j = $i;
        self::addTotalLine("Total Quantité", $quantiteTotale, $ws, $i++);
        self::addTotalLine("Total", '$' . $prixTotal, $ws, $i++);


        $objPHPExcel->getActiveSheet()->getStyle('H' . $j . ':H' . ($j + 1))
            ->applyFromArray($boldArray)
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


        $objPHPExcel->getActiveSheet()->setTitle('Commande Zilu'); //
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($file);


    }

    //--------------------------------------------
    //
    //--------------------------------------------
    private static function start()
    {
        require_once __DIR__ . '/../../www/PHPExcel/Classes/PHPExcel/IOFactory.php';
    }


    private static function arrayToCells(array $headers, array $items, \PHPExcel_Worksheet $ws, &$i)
    {
        $letter = 'A';
        $i = 1;
        foreach ($headers as $label) {
            $ws->setCellValue($letter . $i, $label);
            $letter++;
        }
        $i = 2;
        foreach ($items as $item) {
            $letter = 'A';
            foreach ($item as $label) {
                $ws->setCellValue($letter . $i, $label);
                $letter++;
            }
            $i++;
        }
    }

    private static function addTotalLine($header, $value, \PHPExcel_Worksheet $ws, $rowNumber)
    {
        $ws->setCellValue("H" . $rowNumber, $header);
        $ws->setCellValue("I" . $rowNumber, $value);
    }

}