<?php


namespace CommandeHasArticle;


use Devis\DevisUtil;
use QuickPdo\QuickPdo;

class CommandeHasArticleUtil
{


    public static function updateStatutByCommandeId($commandeId, $statut)
    {
        return QuickPdo::update("commande_has_article", [
            "commande_ligne_statut_id" => $statut,
        ], [
            ['commande_id', "=", $commandeId],
        ]);
    }

    public static function updateStatutByCommandeIdProviderId($commandeId, $providerId, $statut)
    {
        return QuickPdo::update("commande_has_article", [
            "commande_ligne_statut_id" => $statut,
        ], [
            ['commande_id', "=", $commandeId],
            ['fournisseur_id', "=", $providerId],
        ]);
    }

    public static function updateStatut($commandeId, $articleId, $statut)
    {
        return QuickPdo::update("commande_has_article", [
            "commande_ligne_statut_id" => $statut,
        ], [
            ['commande_id', "=", $commandeId],
            ['article_id', "=", $articleId],
        ]);
    }

    public static function getCommandeDetailsByFournisseurId($commandeId, $fournisseurId)
    {


        $query = "select

c.id,
co.id as container_id,
co.nom as container,
c.reference as commande,
a.reference_lf,
a.reference_hldp,
a.photo,
a.logo,
a.long_desc_en,
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
where c.id=" . (int)$commandeId . " and f.id=" . (int)$fournisseurId;


        return QuickPdo::fetchAll($query);

    }


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


    public static function bindContainer($containerId, $commandeId, $articleId, $fournisseurId)
    {
        QuickPdo::update("commande_has_article", [
            'container_id' => $containerId,
        ], [
            ['commande_id', '=', (int)$commandeId],
            ['article_id', '=', (int)$articleId],
            ['fournisseur_id', '=', (int)$fournisseurId],
        ]);
    }


    public static function getDevisByLine($commandeId, $articleId)
    {
        $commandeId = (int)$commandeId;
        $articleId = (int)$articleId;
        return QuickPdo::fetchAll("
select 
d.id,
d.reference,
d.date_reception,
f.nom 
from devis d 
inner join devis_has_commande_has_article hh on hh.devis_id=d.id
inner join commande_has_article h on h.commande_id=hh.commande_has_article_commande_id and h.article_id=hh.commande_has_article_article_id
inner join fournisseur f on f.id=d.fournisseur_id
where 
h.commande_id=$commandeId
and h.article_id=$articleId

        ");
    }

    public static function displayDevisTableByLine($commandeId, $articleId)
    {
        if (false !== ($items = self::getDevisByLine($commandeId, $articleId))) {
            ?>
            <table class="zilu-table-devis"
                   data-cid="<?php echo $commandeId; ?>"
                   data-aid="<?php echo $articleId; ?>"
            >
                <tr>
                    <th>Référence</th>
                    <th>Date réception</th>
                    <th>Fournisseur</th>
                    <th></th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $item['reference']; ?></td>
                        <td><?php echo $item['date_reception']; ?></td>
                        <td><?php echo $item['nom']; ?></td>
                        <td>
                            <button class="devis-remove-bindure" data-did="<?php echo $item['id']; ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr style="height: 20px;">
                    <td colspan="4"></td>
                </tr>
                <tr style="text-align: right;">
                    <td colspan="4">
                        <select class="devis-add-bindure-selector">
                            <option>Associer un devis supplémentaire</option>
                            <?php

                            $items = DevisUtil::getId2Labels();
                            foreach ($items as $id => $label): ?>
                                <option value="<?php echo $id; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="devis-add-bindure">+</button>
                    </td>
                </tr>
            </table>
            <?php
        }
    }
}















