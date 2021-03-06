<?php

use BumbleBee\Autoload\ButineurAutoloader;
use Crud\CrudModule;
use Lang\LangModule;
use Mailer\AppMailer;
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
    ->addLocation(__DIR__ . "/../class-shared")
    ->addLocation(__DIR__ . "/class-modules")
    ->addLocation(__DIR__ . "/class-planets");
ButineurAutoloader::getInst()->start();
require_once __DIR__ . "/vendor/autoload.php"; // composer


//--------------------------------------------
// FUNCTIONS
//--------------------------------------------
require_once __DIR__ . "/functions/main-functions.php";


//--------------------------------------------
// LOCAL VS PROD
//--------------------------------------------
if (true === Helper::isLocal()) {
    // php
    ini_set('display_errors', 1);

    // db
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'non';

    // privilege
    $privilegeSessionTimeout = null; // unlimited session
} else {
    // php
    ini_set('display_errors', 1);

    // db
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'non';

    // privilege
    $privilegeSessionTimeout = 60 * 5;
}


//--------------------------------------------
// PHP
//--------------------------------------------
date_default_timezone_set('Europe/Paris');
ini_set('error_log', __DIR__ . "/log/php.err.log");

//ini_set('session.cookie_lifetime', $privilegeSessionTimeout);
ini_set('session.cookie_lifetime', 10 * 12 * 31 * 86400); // ~10 years
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
//QuickPdo::setConnection("mysql:host=localhost;dbname=$dbName", $dbUser, $dbPass, [
//    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
//    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY','')), NAMES 'utf8'",
////    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'STRICT_TRANS_TABLES','')), NAMES 'utf8'",
//    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//]);


//------------------------------------------------------------------------------/
// MAILER CONFIG
//------------------------------------------------------------------------------/
define('MAILER_HOST', 'smtp.example.org');
define('MAILER_PORT', 25);
define('MAILER_USER', "your username");
define('MAILER_PASS', "your password");
define('MAILER_SENDER', "robot@mywebsite.com");
define('MAILER_ADMIN', "contact@mywebsite.com");
AppMailer::$debug = Helper::isLocal();


//--------------------------------------------
// GENERAL CONFIG
//--------------------------------------------
// paths
define('APP_ROOT_DIR', __DIR__);


// website
// used in mail communication and authentication form,
// used in html title, and at the top of the left menu
define('WEBSITE_NAME', 'My Website');


//--------------------------------------------
// TRANSLATION
//--------------------------------------------
define('APP_DICTIONARY_PATH', APP_ROOT_DIR . "/lang/" . LangModule::getLang('en'));











