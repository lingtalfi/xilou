<?php


use Umail\Umail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// EMBED A FILE
//------------------------------------------------------------------------------/


// $logoFile = __DIR__ . "/myshop-logo.jpg";
$mail = Umail::create();


$vars = function ($email) use ($mail) {


    // normally, you would call some object...
    switch ($email) {
        case 'lingtalfi@gmail.com':
            $ret = [
                'firstname' => 'ling',
                'lastname' => 'talfi',
            ];
            break;
        default:
            $name = explode('@', $email)[0];
            $ret = [
                'firstname' => $name,
                'lastname' => '',
            ];
            break;
    }

    /**
     * The https://tracking_server.php url has two roles (that you must implement server side):
     * - it will display a 1px transparent gif
     * - it will keep track of the date, email of the user
     *
     * In your template, add something like this:
     *
     * <img src="{marker}" alt="tracker gif"/>
     *
     * Since the marker is an embedded image, you need to have
     * the php "allow_url_fopen" directive set to on to embed it.
     * (i.e. although it has tracking capabilities, marker is still just an image)
     *
     */
    //$markerImage = 'https://www.leaderfit-equipement.com/ling/service/tracker.php?email=' . $email;
//    $ret['marker'] = $mail->embedFile($markerImage); // didn't work: file was embedded as an .exe file
    //$ret['marker'] = $markerImage; // worked detecting email opening on my macbookpro,
    return $ret;
};
$commonVars = [
    'shop_name' => 'my shop',
    'shop_url' => 'http://my_shop.com',
    //'shop_logo' => $mail->embedFile($logoFile),
];
$res = $mail->to([
    'lingtalfi@gmail.com' => 'Ling',
    //'delphine@myshop.com',
])
    ->from('johndoe@gmail.com')
    ->subject("Hi, testing template")
    ->setVars($commonVars, $vars)
    ->setTemplate('log_alert')
    ->send();
a($res);