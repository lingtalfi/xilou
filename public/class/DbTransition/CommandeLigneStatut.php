<?php


namespace DbTransition;

use QuickPdo\QuickPdo;

class CommandeLigneStatut{

    public static function create(){
        $status = [
            "Pas encore traité",
            "Envoyé par mail à la direction",
            "Devis envoyé par mail au fournisseur",
            "Proformat Invoice confirmé avec signature",
            "30% payé par Leaderfit",
            "70% à payer",
            "Terminé",
        ];
        foreach ($status as $etat) {

            if (false !== $id = (QuickPdo::insert("commande_ligne_statut", [
                    'nom' => $etat,
                ]))
            ) {
                $commandeLigneStatutIds[] = $id;
            }
        }
    }

}