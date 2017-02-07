<?php

use Knp\Snappy\Pdf;

// http://bootsnipp.com/snippets/4loVx
// https://htmlpdfapi.com/blog/free_html5_invoice_templates


if ('snappy') {

    require_once "bigbang.php";
    require __DIR__ . '/../vendor/autoload.php';
    $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
//header('Content-Type: application/pdf');
//header('Content-Disposition: attachment; filename="file.pdf"');
    ob_start();
    include __DIR__ . "/pdf.html.php";
    $content = ob_get_clean();
    $f = "/tmp/test.pdf";
    unlink($f);
    $snappy->generateFromHtml($content, $f);
}
