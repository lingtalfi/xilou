<?php


use Boot\BootConfig;
use Boot\BootModule;
use Events\EventsServices;
use Privilege\PrivilegeUser;
use Router\RouterServices;

if (file_exists(__DIR__ . "/../init.php")) {
    require_once __DIR__ . "/../init.php";
} else {
    require_once __DIR__ . "/../init-fallback.php";
}



//--------------------------------------------
// ROUTER
//--------------------------------------------
ob_start(); // ob start gives us the ability to do redirect from php "view" code, using header(location...); exit;
if (PrivilegeUser::isConnected()) {


    if (true === BootModule::applicationIsInitialized()) {

        /**
         * THIS IS THE ROUTER MAP.
         * Add your page routes here...
         *
         */
        $uri2pagesMap = [
            '/' => 'home.php',
            '/commande' => 'commande.php',
            '/container' => 'container.php',
            '/sav' => 'sav.php',
            '/test' => 'test.php',

            //--------------------------------------------
            // LING, don't worry about the links below this line, they are personal stuff
            //--------------------------------------------
            '/formgen' => 'ling/formgen-test.php',
            '/listgen' => 'ling/listgen-test.php',
            '/prefs' => 'ling/prefs-test.php',
        ];


        RouterServices::decorateUri2PagesMap($uri2pagesMap);


        /**
         * This is the router code, you shouldn't edit below this line
         */
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        if ('' !== URL_PREFIX && URL_PREFIX === substr($uri, 0, strlen(URL_PREFIX))) {
            $uri = substr($uri, strlen(URL_PREFIX));
        }

        if (strlen($uri) > 1) {
            $uri = rtrim($uri, '/'); // I had the problem with /stats/ instead of /stats, not sure why
        }
        Spirit::set('uri', $uri);


        $page = "404.php";
        if (array_key_exists($uri, $uri2pagesMap)) {
            $page = $uri2pagesMap[$uri];
        }

    } else {
        $page = BootConfig::getBootPage();
        Spirit::set('uri', BootConfig::getBootUri());
    }

} else {
    $page = 'login.php';
}

require_once APP_ROOT_DIR . "/pages/" . $page;
echo ob_get_clean();

EventsServices::onPageRenderedAfter();