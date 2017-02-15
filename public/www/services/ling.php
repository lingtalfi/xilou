<?php


require_once "bigbang.php";

if (array_key_exists('item', $_POST)) {
    $item = $_POST['item'];
    file_put_contents('items.txt', json_encode($item) . PHP_EOL, FILE_APPEND);
}

