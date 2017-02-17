<?php


use AdminTable\Listable\QuickPdoListable;
use AdminTable\View\AdminTableRenderer;
use Commande\AdminTable\CommandeAdminTable;
use Csv\CsvUtil;
use CsvImport\CommandeImporterUtil;
use DbTransition\CommandeLigneStatut;
use QuickPdo\QuickPdo;
use Util\ArrayRenderer;
use Util\GeneralUtil;
use Util\RowsRenderer;

require_once __DIR__ . "/../init.php";




$f = "/Users/pierrelafitte/Downloads/COMMANDE ZILU 02-2017.xlsx";
$f = "/Users/lafitte/Downloads/COMMANDE ZILU 02-2017.xlsx";





$fields = '
c.id,
co.id as container_id,
co.nom as container,
c.reference,
f.id as fournisseur_id,
f.nom as fournisseur,
fha.prix,
fha.poids,
fha.volume,
h.prix_override,
h.quantite,
h.date_estimee,
a.id as aid,
a.reference_lf,
a.reference_hldp,
a.descr_fr,
a.descr_en,
h.sav_id as sav
';


$fields = '
c.id,
#co.id as container_id,
#co.nom as container,
c.reference,
f.id as fournisseur_id,
f.nom as fournisseur,
fha.prix,
fha.poids,
fha.volume,
h.prix_override,
h.quantite,
h.date_estimee,
#a.id as aid,
#a.reference_lf,
#a.reference_hldp,
#a.descr_fr,
#a.descr_en,
h.sav_id as sav
';


$idCommande = 2;

$query = "select
%s
from zilu.commande c
inner join commande_has_article h on h.commande_id=c.id
inner join fournisseur f on f.id=h.fournisseur_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
inner join article a on a.id=h.article_id
left join container co on co.id=h.container_id
where c.id=" . $idCommande;

$query = "select
%s
from zilu.commande c
inner join commande_has_article h on h.commande_id=c.id
inner join fournisseur f on f.id=h.fournisseur_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
where c.id=" . $idCommande;





$list = CommandeAdminTable::create()
    ->setRic(['id', 'aid'])
    ->setListable(QuickPdoListable::create()->setFields($fields)->setQuery($query))
    ->setRenderer(AdminTableRenderer::create()
    );

$list->displayTable();