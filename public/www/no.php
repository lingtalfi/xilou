<?php


use Mail\OrderConfMail;
use Mail\OrderProviderConfMail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// EMBED A FILE
//------------------------------------------------------------------------------/

/**
 * Fournisseur
 * Ref LF
 * Produit
 * Livré le
 * Quantité
 * Prix
 * Nbre de pdts défectueux
 * Date de notification
 * Demande de remboursement
 * Montant Remboursé
 * Remboursement
 * Forme
 * Statut
 * Date du remboursement
 * Problèmes
 * photos
 * Avancement
 */
OrderProviderConfMail::create()->send([
    'lingtalfi@gmail.com' => 'ling',
//            'zilu@leaderfit.com' => 'zilu',
]);