<?php


namespace Sav;


use QuickPdo\QuickPdo;

class SavUtil
{


    public static function addByCommandLine($commandeId, $articleId, array $data)
    {

        $commandeId = (int)$commandeId;
        $articleId = (int)$articleId;


        if (false !== ($res = QuickPdo::fetch(
                'select 
f.nom as fournisseur,
a.reference_lf,
a.descr_fr,
h.date_estimee,
h.quantite,
fh.prix
 

from commande_has_article h
inner join fournisseur f on f.id=h.fournisseur_id
inner join article a on a.id=h.article_id
inner join fournisseur_has_article fh on fh.fournisseur_id=f.id and fh.article_id=a.id
where h.commande_id=' . $commandeId . '
and h.article_id=' . $articleId
            ))
        ) {


            $data = array_merge([
                'nb_produits_defec' => "",
                'date_notif' => "",
                'demande_remboursement' => "",
                'montant_rembourse' => "",
                'pourcentage_rembourse' => "",
                'date_remboursement' => "",
                'forme' => "",
                'statut' => "",
                'photo' => "",
                'avancement' => "",
            ], $data);


            if ('' === $data['date_remboursement']) {
                $data['date_remboursement'] = null;
            }
            if ('' === $data['date_notif']) {
                $data['date_notif'] = null;
            }

            if (false !== ($id = QuickPdo::insert('sav', [
                    'fournisseur' => $res['fournisseur'],
                    'reference_lf' => $res['reference_lf'],
                    'produit' => substr($res['descr_fr'], 0, 60), // limit 64 by mysql
                    'livre_le' => $res['date_estimee'],
                    'quantite' => (int)$res['quantite'],
                    'prix' => $res['prix'],
                    'nb_produits_defec' => (int)$data['nb_produits_defec'],
                    'date_notif' => $data['date_notif'],
                    'demande_remboursement' => (float)$data['demande_remboursement'],
                    'montant_rembourse' => (float)$data['montant_rembourse'],
                    'pourcentage_rembourse' => (int)$data['pourcentage_rembourse'],
                    'date_remboursement' => $data['date_remboursement'],
                    'forme' => $data['forme'],
                    'statut' => $data['statut'],
                    'photo' => $data['photo'],
                    'avancement' => $data['avancement'],
                ]))
            ) {
                QuickPdo::update('commande_has_article', ['sav_id' => $id], [
                    ["commande_id", '=', $commandeId],
                    ["article_id", '=', $articleId],
                ]);
                return true;
            }
        }
        return false;
    }


}