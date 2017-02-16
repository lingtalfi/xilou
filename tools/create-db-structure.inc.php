<?php


use QuickPdo\QuickPdo;




QuickPdo::freeExec(file_get_contents(__DIR__ . "/assets/zilu-structure.sql"));
$d = __DIR__ . "/assets";
QuickPdo::freeQuery(file_get_contents($d . "/csv_fournisseurs_comparatif.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_fournisseurs_containers.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_fournisseurs_fournisseurs.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_fournisseurs_sav.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_prix_materiel.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_product_details.sql"));
QuickPdo::freeQuery(file_get_contents($d . "/csv_product_list.sql"));