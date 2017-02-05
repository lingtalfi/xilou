<?php


use QuickPdo\QuickPdo;

$dbUser = 'root';
$dbPass = '';
$dbName = 'zilu';

if ('/Volumes/' === substr(__DIR__, 0, 9)) {
    $dbPass = 'root';
}


QuickPdo::setConnection("mysql:host=localhost;dbname=$dbName", $dbUser, $dbPass, [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY','')), NAMES 'utf8'",
//    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'STRICT_TRANS_TABLES','')), NAMES 'utf8'",
//    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

