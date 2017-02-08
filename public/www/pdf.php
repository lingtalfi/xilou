<?php


require "bigbang.php";

require_once __DIR__ . "/TCPDF/tcpdf.php";
//require_once(__DIR__ . '/TCPDF/config/tcpdf_config.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
//$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Nicola EEE');
//$pdf->SetTitle('TCPDF Example 001');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
//
//// set default header data
//
//
//
//
////$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));
//
//// set header and footer fonts
////$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//
//// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//
//// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//
//// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//
//// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//
//// set some language-dependent strings (optional)
//if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
//    require_once(dirname(__FILE__).'/lang/eng.php');
//    $pdf->setLanguageArray($l);
//}

// ---------------------------------------------------------


$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

// add a page

// create some HTML content
$subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';


$imgPath = __DIR__ . '/img/leaderfit-logo.jpg';

$imgSrc = 'data:image/$ext;base64,' . base64_encode(file_get_contents($imgPath));


for ($i = 0; $i < 20; $i++) {

    $html = '

<table border="0.5" cellspacing="0" cellpadding="4" bgcolor="red" color="white">
    <tr>
        <th><img src="' . $imgSrc . '" alt="logo"></th>
        <th align="right">RIGHT align</th>
        <th align="left">LEFT align</th>
        <th>4A</th>
    </tr>
</table>
';

// output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
}


$pdf->Image($imgPath, '', '', 50);
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');
