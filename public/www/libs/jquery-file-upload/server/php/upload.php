<?php


require_once __DIR__ . '/../../../../../init.php';

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$upload_handler = new UploadHandler([
    'param_name' => 'photo',
]);
