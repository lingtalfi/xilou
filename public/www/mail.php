<?php


use Umail\Umail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// EMBED A FILE
//------------------------------------------------------------------------------/
$file = "/Users/pierrelafitte/Desktop/ps_product_attribute.png";

$mail = Umail::create();
$cid = $mail->embedFile($file);
$res = $mail->to([
    'lingtalfi@gmail.com' => 'ling',
])
    ->from('johndoe@gmail.com')
    ->subject("Hi, testing attach file")
    ->htmlBody(
        '<html>' .
        ' <head></head>' .
        ' <body>' .
        '  Here is an image <img src="' . // Embed the file
        $cid .
        '" alt="Image" />' .
        '  Rest of message' .
        ' </body>' .
        '</html>'
    )
    ->plainBody('Hi, this is just a test message to test file attachment')
    ->send();
a($res);