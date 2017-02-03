<?php


$ll = 'modules/frontOne/frontOne';
Spirit::set('ll', $ll); // for linkt
define('LL', $ll); // translation context


$action = 'list';
if (array_key_exists('action', $_GET)) {
    $action = $_GET['action'];
}


if ('edit' !== $action) {
    $action = 'list';
}

require_once __DIR__ . "/actions/" . $action . '.php';
