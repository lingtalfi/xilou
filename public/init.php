<?php

use BumbleBee\Autoload\ButineurAutoloader;
use Lang\LangModule;
use Privilege\Privilege;
use Privilege\PrivilegeUser;
use QuickPdo\QuickPdo;


//------------------------------------------------------------------------------/
// UNIVERSE AUTOLOADER (bigbang)
//------------------------------------------------------------------------------/
require_once __DIR__ . '/class-planets/BumbleBee/Autoload/BeeAutoloader.php';
require_once __DIR__ . '/class-planets/BumbleBee/Autoload/ButineurAutoloader.php';
ButineurAutoloader::getInst()
    ->addLocation(__DIR__ . "/class")
    ->addLocation(__DIR__ . "/class-core")
    ->addLocation(__DIR__ . "/class-modules")
    ->addLocation(__DIR__ . "/class-planets");
ButineurAutoloader::getInst()->start();
require_once __DIR__ . '/vendor/autoload.php';


//--------------------------------------------
// FUNCTIONS
//--------------------------------------------
require_once __DIR__ . "/functions/main-functions.php";


//--------------------------------------------
// LOCAL VS PROD
//--------------------------------------------
if (
    "/Users/" === substr(__DIR__, 0, 7) ||
    "/Volumes/" === substr(__DIR__, 0, 9)
) {
    // php
    ini_set('display_errors', 1);

    // db
    $dbUser = 'root';
    $dbPass = 'root';
    $dbName = 'zilu';
    $host = 'localhost';

    if ("/Users/" === substr(__DIR__, 0, 7)) {
        $dbPass = '';
        $host = '127.0.0.1';

    }


    // privilege
    $privilegeSessionTimeout = null; // unlimited session
} else {
    // php
    ini_set('display_errors', 0);


    // db
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'zilu';


    // privilege
    $privilegeSessionTimeout = 60 * 5;
}

//--------------------------------------------
// PHP
//--------------------------------------------
date_default_timezone_set("Europe/Paris");
ini_set('error_log', __DIR__ . "/log/php.err.log");
if (null !== $privilegeSessionTimeout) { // or session expires when browser quits
    ini_set('session.cookie_lifetime', $privilegeSessionTimeout);
} else {
    ini_set('session.cookie_lifetime', 10 * 12 * 31 * 86400); // ~10 years
}

session_start();


//--------------------------------------------
// REDIRECTION
//--------------------------------------------
if ('/index.php' === $_SERVER['PHP_SELF']) {
    define('URL_PREFIX', '');
} else {

    define('URL_PREFIX', substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
}


//--------------------------------------------
// DATABASE CONNEXION
//--------------------------------------------
QuickPdo::setConnection("mysql:host=$host;dbname=$dbName", $dbUser, $dbPass, [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY','')), NAMES 'utf8'",
//    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'STRICT_TRANS_TABLES','')), NAMES 'utf8'",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);


//--------------------------------------------
// GENERAL CONFIG
//--------------------------------------------
// paths
define('APP_ROOT_DIR', __DIR__);
define('PATH_TO_MYSQLDUMP', "/usr/local/mysql/bin/mysqldump"); // might be different on zilu's computer
define('PATH_TO_MYSQL', "/usr/local/mysql/bin/mysql"); // might be different on zilu's computer
define('APP_COMMANDE_IMPORTS_DIR', APP_ROOT_DIR . "/www/commande-imports");
define('MAIL_ZILU', 'lingtalfi@gmail.com');
define('MAIL_DIDIER', 'lingtalfi@gmail.com');
define('MAIL_FROM', 'zilu-bot@leaderfit-equipement.com');


// website
// used in mail communication and authentication form,
// used in html title, and at the top of the left menu
define('WEBSITE_NAME', "Zilu's interface");


Spirit::set('ricSeparator', '--*--');


//--------------------------------------------
// PRIVILEGE
//--------------------------------------------
PrivilegeUser::$sessionTimeout = 60 * 5 * 1000;
PrivilegeUser::refresh();
if (array_key_exists('disconnect', $_GET)) {
    PrivilegeUser::disconnect();
    if ('' !== $_SERVER['HTTP_REFERER']) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
Privilege::setProfiles([
    'root' => [
        '*',
    ],
    'admin' => [],
]);


//--------------------------------------------
// TRANSLATION
//--------------------------------------------
define('APP_DICTIONARY_PATH', APP_ROOT_DIR . "/lang/" . LangModule::getLang("en"));







        
        