<?php

use Crud\CrudHelper;
use Crud\CrudModule;

$fields = '
id,
ref_hldp,
ref_lf,
produit,
m3,
gw,
nw,
vendu_par,
ean,
nom_hldp,
nom_leaderfit,
poids,
materiaux,
etat_import,
largeur,
hauteur,
longueur,
resistance,
autres,
MOQ,
packaging,
categorie,
descriptif,
url,
en_products,
en_sold_by,
en_packaging,
en_material,
en_description,
en_category,
es_products,
es_sold_by,
es_packaging,
es_material,
es_category,
moyenne,
wohlstand,
rising,
top_asia,
azuni,
kylin,
modern_sports,
gyco,
lion,
live_up,
ironmaster,
record,
tengtai,
dekai,
alex,
regal,
helisports,
amaya,
msd,
fournisseur,
unit,
pa_dollar,
pa_fdp_inclus,
ob_marge_hldp,
ob_pv_fob_dollar,
ob_pv_fob,
ob_pv_hldp_dollar,
ob_pv_hldp,
pv_lf_orange,
reduction,
produit_specifique,
rev_marge_hldp,
rev_pv_fob_dollar,
rev_pv_fob,
rev_pv_hldp_dollar,
rev_pv_hldp,
gev_marge_hldp,
gev_pv_fob_dollar,
gev_pv_fob,
gev_pv_hldp_dollar,
gev_pv_hldp,
gev_pv_hldp2,
gev_pv_hldp3,
cha_marge_hldp,
cha_pv_fob_dollar,
cha_pv_fob,
cha_pv_hldp_dollar,
cha_pv_hldp,
cha_pv_hldp2,
kin_marge_hldp,
kin_pv_fob_dollar,
kin_pv_fob,
kin_pv_hldp_dollar,
kin_pv_hldp,
kin_pv_hldp2,
fit_marge_hldp,
fit_pv_fob_dollar,
fit_pv_fob,
fit_pv_hldp_dollar,
fit_pv_hldp,
fit_pv_hldp2,
lf_pv_public,
lf_pv_public_dollar,
lf_reduction,
lf_pv_revendeur,
lf_pv_revendeur_dollar
';


$query = "select
%s
from zilu.csv_fournisseurs_comparatif
";


$table = CrudModule::getDataTable("zilu.csv_fournisseurs_comparatif", $query, $fields, ['id']);

$table->title = "Csv fournisseurs comparatif";


$table->columnLabels= [
    "id" => "id",
    "ref_hldp" => "ref hldp",
    "ref_lf" => "ref lf",
    "produit" => "produit",
    "m3" => "m3",
    "gw" => "gw",
    "nw" => "nw",
    "vendu_par" => "vendu par",
    "ean" => "ean",
    "nom_hldp" => "nom hldp",
    "nom_leaderfit" => "nom leaderfit",
    "poids" => "poids",
    "materiaux" => "materiaux",
    "etat_import" => "etat import",
    "largeur" => "largeur",
    "hauteur" => "hauteur",
    "longueur" => "longueur",
    "resistance" => "resistance",
    "autres" => "autres",
    "MOQ" => "MOQ",
    "packaging" => "packaging",
    "categorie" => "categorie",
    "descriptif" => "descriptif",
    "url" => "url",
    "en_products" => "en products",
    "en_sold_by" => "en sold by",
    "en_packaging" => "en packaging",
    "en_material" => "en material",
    "en_description" => "en description",
    "en_category" => "en category",
    "es_products" => "es products",
    "es_sold_by" => "es sold by",
    "es_packaging" => "es packaging",
    "es_material" => "es material",
    "es_category" => "es category",
    "moyenne" => "moyenne",
    "wohlstand" => "wohlstand",
    "rising" => "rising",
    "top_asia" => "top asia",
    "azuni" => "azuni",
    "kylin" => "kylin",
    "modern_sports" => "modern sports",
    "gyco" => "gyco",
    "lion" => "lion",
    "live_up" => "live up",
    "ironmaster" => "ironmaster",
    "record" => "record",
    "tengtai" => "tengtai",
    "dekai" => "dekai",
    "alex" => "alex",
    "regal" => "regal",
    "helisports" => "helisports",
    "amaya" => "amaya",
    "msd" => "msd",
    "fournisseur" => "fournisseur",
    "unit" => "unit",
    "pa_dollar" => "pa dollar",
    "pa_fdp_inclus" => "pa fdp inclus",
    "ob_marge_hldp" => "ob marge hldp",
    "ob_pv_fob_dollar" => "ob pv fob dollar",
    "ob_pv_fob" => "ob pv fob",
    "ob_pv_hldp_dollar" => "ob pv hldp dollar",
    "ob_pv_hldp" => "ob pv hldp",
    "pv_lf_orange" => "pv lf orange",
    "reduction" => "reduction",
    "produit_specifique" => "produit specifique",
    "rev_marge_hldp" => "rev marge hldp",
    "rev_pv_fob_dollar" => "rev pv fob dollar",
    "rev_pv_fob" => "rev pv fob",
    "rev_pv_hldp_dollar" => "rev pv hldp dollar",
    "rev_pv_hldp" => "rev pv hldp",
    "gev_marge_hldp" => "gev marge hldp",
    "gev_pv_fob_dollar" => "gev pv fob dollar",
    "gev_pv_fob" => "gev pv fob",
    "gev_pv_hldp_dollar" => "gev pv hldp dollar",
    "gev_pv_hldp" => "gev pv hldp",
    "gev_pv_hldp2" => "gev pv hldp2",
    "gev_pv_hldp3" => "gev pv hldp3",
    "cha_marge_hldp" => "cha marge hldp",
    "cha_pv_fob_dollar" => "cha pv fob dollar",
    "cha_pv_fob" => "cha pv fob",
    "cha_pv_hldp_dollar" => "cha pv hldp dollar",
    "cha_pv_hldp" => "cha pv hldp",
    "cha_pv_hldp2" => "cha pv hldp2",
    "kin_marge_hldp" => "kin marge hldp",
    "kin_pv_fob_dollar" => "kin pv fob dollar",
    "kin_pv_fob" => "kin pv fob",
    "kin_pv_hldp_dollar" => "kin pv hldp dollar",
    "kin_pv_hldp" => "kin pv hldp",
    "kin_pv_hldp2" => "kin pv hldp2",
    "fit_marge_hldp" => "fit marge hldp",
    "fit_pv_fob_dollar" => "fit pv fob dollar",
    "fit_pv_fob" => "fit pv fob",
    "fit_pv_hldp_dollar" => "fit pv hldp dollar",
    "fit_pv_hldp" => "fit pv hldp",
    "fit_pv_hldp2" => "fit pv hldp2",
    "lf_pv_public" => "lf pv public",
    "lf_pv_public_dollar" => "lf pv public dollar",
    "lf_reduction" => "lf reduction",
    "lf_pv_revendeur" => "lf pv revendeur",
    "lf_pv_revendeur_dollar" => "lf pv revendeur dollar",
];


$table->hiddenColumns = [
    "id",
];


$n = 30;
$table->setTransformer('produit', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('nom_hldp', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('nom_leaderfit', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('descriptif', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('en_description', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});
$table->setTransformer('es_products', function ($v) use ($n) {
    return substr($v, 0, $n) . '...';
});


$table->displayTable();
