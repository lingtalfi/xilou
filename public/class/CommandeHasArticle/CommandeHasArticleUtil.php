<?php


namespace CommandeHasArticle;


use QuickPdo\QuickPdo;

class CommandeHasArticleUtil
{

    public static function getCommandeDetails($commandeId)
    {


        $query = "select

c.id,
co.id as container_id,
co.nom as container,
c.reference as commande,
a.reference_lf,
a.reference_hldp,
a.photo,
f.id as fournisseur_id,
f.nom as fournisseur,
fha.prix,
fha.poids,
fha.volume,
fha.reference as reference_pro,
h.prix_override,
h.quantite,
h.date_estimee,
h.unit,
a.id as aid,
a.descr_fr,
a.descr_en,
a.ean,
h.sav_id as sav


from zilu.commande c
inner join commande_has_article h on h.commande_id=c.id
inner join fournisseur f on f.id=h.fournisseur_id
inner join fournisseur_has_article fha on fha.fournisseur_id=h.fournisseur_id and fha.article_id=h.article_id
inner join article a on a.id=h.article_id
left join container co on co.id=h.container_id
where c.id=" . $commandeId;


        return QuickPdo::fetchAll($query);

    }

}