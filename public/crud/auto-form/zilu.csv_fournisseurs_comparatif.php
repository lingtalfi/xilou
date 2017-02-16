<?php


use Crud\CrudModule;

$form = CrudModule::getForm("zilu.csv_fournisseurs_comparatif", ['id']);



$form->labels = [
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


$form->title = "Csv fournisseurs comparatif";


$form->addControl("ref_hldp")->type("text");
$form->addControl("ref_lf")->type("text");
$form->addControl("produit")->type("message");
$form->addControl("m3")->type("text");
$form->addControl("gw")->type("text");
$form->addControl("nw")->type("text");
$form->addControl("vendu_par")->type("text");
$form->addControl("ean")->type("text");
$form->addControl("nom_hldp")->type("message");
$form->addControl("nom_leaderfit")->type("message");
$form->addControl("poids")->type("text");
$form->addControl("materiaux")->type("text");
$form->addControl("etat_import")->type("text");
$form->addControl("largeur")->type("text");
$form->addControl("hauteur")->type("text");
$form->addControl("longueur")->type("text");
$form->addControl("resistance")->type("text");
$form->addControl("autres")->type("text");
$form->addControl("MOQ")->type("text");
$form->addControl("packaging")->type("text");
$form->addControl("categorie")->type("text");
$form->addControl("descriptif")->type("message");
$form->addControl("url")->type("text");
$form->addControl("en_products")->type("text");
$form->addControl("en_sold_by")->type("text");
$form->addControl("en_packaging")->type("text");
$form->addControl("en_material")->type("text");
$form->addControl("en_description")->type("message");
$form->addControl("en_category")->type("text");
$form->addControl("es_products")->type("message");
$form->addControl("es_sold_by")->type("text");
$form->addControl("es_packaging")->type("text");
$form->addControl("es_material")->type("text");
$form->addControl("es_category")->type("text");
$form->addControl("moyenne")->type("text");
$form->addControl("wohlstand")->type("text");
$form->addControl("rising")->type("text");
$form->addControl("top_asia")->type("text");
$form->addControl("azuni")->type("text");
$form->addControl("kylin")->type("text");
$form->addControl("modern_sports")->type("text");
$form->addControl("gyco")->type("text");
$form->addControl("lion")->type("text");
$form->addControl("live_up")->type("text");
$form->addControl("ironmaster")->type("text");
$form->addControl("record")->type("text");
$form->addControl("tengtai")->type("text");
$form->addControl("dekai")->type("text");
$form->addControl("alex")->type("text");
$form->addControl("regal")->type("text");
$form->addControl("helisports")->type("text");
$form->addControl("amaya")->type("text");
$form->addControl("msd")->type("text");
$form->addControl("fournisseur")->type("text");
$form->addControl("unit")->type("text");
$form->addControl("pa_dollar")->type("text");
$form->addControl("pa_fdp_inclus")->type("text");
$form->addControl("ob_marge_hldp")->type("text");
$form->addControl("ob_pv_fob_dollar")->type("text");
$form->addControl("ob_pv_fob")->type("text");
$form->addControl("ob_pv_hldp_dollar")->type("text");
$form->addControl("ob_pv_hldp")->type("text");
$form->addControl("pv_lf_orange")->type("text");
$form->addControl("reduction")->type("text");
$form->addControl("produit_specifique")->type("text");
$form->addControl("rev_marge_hldp")->type("text");
$form->addControl("rev_pv_fob_dollar")->type("text");
$form->addControl("rev_pv_fob")->type("text");
$form->addControl("rev_pv_hldp_dollar")->type("text");
$form->addControl("rev_pv_hldp")->type("text");
$form->addControl("gev_marge_hldp")->type("text");
$form->addControl("gev_pv_fob_dollar")->type("text");
$form->addControl("gev_pv_fob")->type("text");
$form->addControl("gev_pv_hldp_dollar")->type("text");
$form->addControl("gev_pv_hldp")->type("text");
$form->addControl("gev_pv_hldp2")->type("text");
$form->addControl("gev_pv_hldp3")->type("text");
$form->addControl("cha_marge_hldp")->type("text");
$form->addControl("cha_pv_fob_dollar")->type("text");
$form->addControl("cha_pv_fob")->type("text");
$form->addControl("cha_pv_hldp_dollar")->type("text");
$form->addControl("cha_pv_hldp")->type("text");
$form->addControl("cha_pv_hldp2")->type("text");
$form->addControl("kin_marge_hldp")->type("text");
$form->addControl("kin_pv_fob_dollar")->type("text");
$form->addControl("kin_pv_fob")->type("text");
$form->addControl("kin_pv_hldp_dollar")->type("text");
$form->addControl("kin_pv_hldp")->type("text");
$form->addControl("kin_pv_hldp2")->type("text");
$form->addControl("fit_marge_hldp")->type("text");
$form->addControl("fit_pv_fob_dollar")->type("text");
$form->addControl("fit_pv_fob")->type("text");
$form->addControl("fit_pv_hldp_dollar")->type("text");
$form->addControl("fit_pv_hldp")->type("text");
$form->addControl("fit_pv_hldp2")->type("text");
$form->addControl("lf_pv_public")->type("text");
$form->addControl("lf_pv_public_dollar")->type("text");
$form->addControl("lf_reduction")->type("text");
$form->addControl("lf_pv_revendeur")->type("text");
$form->addControl("lf_pv_revendeur_dollar")->type("text");


$form->display();
