<?php


namespace CommandeLigneStatut;

class CommandeLigneStatutUtil
{
    const STATUT_UN_PAS_ENCORE_TRAITE = 1;
    const STATUT_DEUX_ENVOYE_PAR_MAIL_A_DIDIER = 2;
    const STATUT_TROIS_ENVOYE_PAR_MAIL_AUX_FOURNISSEURS = 3;
    const STATUT_QUATRE_PROFORMAT_INVOICE_CONFIRME_AVEC_SIGNATURE = 4;
    const STATUT_CINQ_TRENTE_POURCENT_PAYE_PAR_LEADERFIT = 5;
    const STATUT_SIX_SOIXANTE_DIX_POURCENT_A_PAYER = 6;
    const STATUT_SEPT_FINI = 7;

    private static $statuts = [
        self::STATUT_UN_PAS_ENCORE_TRAITE => "pas encore traité",
        self::STATUT_DEUX_ENVOYE_PAR_MAIL_A_DIDIER => "envoyé par mail à didier",
        self::STATUT_TROIS_ENVOYE_PAR_MAIL_AUX_FOURNISSEURS => "envoyé par mail aux fournisseurs",
        self::STATUT_QUATRE_PROFORMAT_INVOICE_CONFIRME_AVEC_SIGNATURE => "invoice confirmé",
        self::STATUT_CINQ_TRENTE_POURCENT_PAYE_PAR_LEADERFIT => "30% payé",
        self::STATUT_SIX_SOIXANTE_DIX_POURCENT_A_PAYER => "70% à payer",
        self::STATUT_SEPT_FINI => "fini",
    ];


    public static function toString($statut)
    {
        return self::$statuts[$statut];
    }

    public static function getIds2Labels()
    {
        return self::$statuts;
    }
}