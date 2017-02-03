<?php


use Commande\CommandeUtil;

require_once __DIR__ . "/../../init.php";


$output = '';
if (array_key_exists('action', $_GET)) {
    $param = null;
    if (array_key_exists('param', $_POST)) {
        $param = $_POST['param'];
    }
    $action = $_GET['action'];
    switch ($action) {
        case 'commande-selector':
            $output = CommandeUtil::getId2Labels();
            break;
        default;
            break;
    }
}


echo json_encode($output);

