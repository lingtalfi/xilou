<?php


require "bigbang.php";
$a = [
    "sandy" => "tulipe",
    "roger" => "rabbit",
    "johanna" => "assume",
];


$b = [
    "george" => "washington",
];

a(array_merge($a, $b));
