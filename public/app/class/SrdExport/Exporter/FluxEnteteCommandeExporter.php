<?php


namespace SrdExport\Exporter;


use QuickPdo\QuickPdo;
use SrdExport\Exporter\Helper\ExporterHelper;


/**
 * Deprecated: see FluxEnteteCommandeExporterWithAddress for updated version
 */
class FluxEnteteCommandeExporter extends CsvExporter
{
    public function __construct()
    {
        parent::__construct();
        $this->setSettings([
            'code_enseigne' => ['C', 1, 0],
            'numero_commande' => ['I', 7, 0],
            'numero_dossier' => ['C', 15, 0],
            'code_client' => ['C', 7, 0],
            'argument_client' => ['C', 15, 0],
            'pave_adresse_facturation' => ['C', 32, 0],
            'pave_adresse_livraison' => ['C', 32, 0],
            'argument_adresse_livraison' => ['C', 10, 0],
            'date_commande' => ['D', 10, 0],
            'heure_minutes' => ['C', 5, 0],
            'mode_paiement' => ['C', 6, 0],
//            'type_cb' => ['C', 1, 0],
//            'numero_cb' => ['C', 19, 0],
//            'crypto' => ['C', 3, 0],
//            'date_validite' => ['C', 7, 0],
//            'numero_autorisation' => ['C', 6, 0],
            'support_commande' => ['C', 5, 0],
            'montant_total_produit' => ['C', 12, 2],
            'frais_port' => ['C', 12, 2],
            'frais_crt' => ['C', 12, 2],
            'frais_expedition' => ['C', 12, 2],
            'remise_support' => ['C', 12, 2],
            'total_nap' => ['C', 12, 2],
            'total_tva' => ['C', 12, 2],
            'montant_paiements' => ['C', 12, 2],
            'solde' => ['C', 12, 2],
            'mode_expedition' => ['C', 8, 0],
            'top_nastva' => ['C', 1, 0],
            'login' => ['C', 15, 0],
            'mot_de_passe' => ['C', 15, 0],
            'email' => ['C', 48, 0],
            'donnees_cb_cryptees' => ['C', 32, 0],
            'numero_appel' => ['C', 10, 0],
            'numero_transaction' => ['C', 10, 0],
            'ref_abonne' => ['C', 32, 0],
            'acquereur' => ['C', 16, 0],
            'controle' => ['C', 1, 0],
        ]);
    }


    public static function createByHookParams(array $params)
    {

        if (array_key_exists('id_order', $params)) {
            // todo: check cart id_order, ...
        }

        $orderId = (int)$params['id_order'];
        $cart = $params['cart'];

        $total_cart_ttc_without_reduction_without_shipping = ExporterHelper::getTotalTtcWithoutReductionWithoutShippingByCart($cart);
        $o = self::create();
        $res = QuickPdo::fetch('
select
 
o.id_carrier,
o.id_cart,
o.payment,
o.total_products as montant_total_produit,
o.total_paid_tax_incl,
o.total_paid_tax_excl,
o.total_shipping_tax_excl as frais_port,
o.date_add as date_commande,
c.email

from
ps_orders o
inner join ps_customer c on c.id_customer=o.id_customer 
where o.id_order=' . $orderId . '
');


        $total_cart_ttc_with_reduction_without_shipping = $res['total_products_wt'] + $res['total_discounts_tax_incl'];
        $reduction_ttc = $total_cart_ttc_without_reduction_without_shipping - $total_cart_ttc_with_reduction_without_shipping;


        // todo: demander profileo calcul montantTotalProduit HT ou TTC, avec ou sans réduction...
        $montantTotalProduitHt = $res['montant_total_produit'];
//        $montantTotalProduit = $total_cart_ttc_without_reduction_without_shipping;

        $fraisPortHt = $res['frais_port'];
        $totalNap = $montantTotalProduitHt + $fraisPortHt + $reduction_ttc;
        $totalTva = $res['total_paid_tax_incl'] - $res['total_paid_tax_excl'];
        $montantPaiements = 0;
        // todo: or use module field in ps_orders? for safer computation
        if (in_array($res['payment'], ['Virement bancaire'], true)) {
            $montantPaiements = $res['total_paid_tax_incl'];
        }


        $solde = $totalNap + $totalTva + $montantPaiements;
        $hasTva = ($res['total_paid_tax_excl'] !== $res['total_paid_tax_incl']);
        $topNasTva = (int)$hasTva;
        $numeroAppel = "";
        if (false === "paybox") {
            $numeroAppel = $res['id_cart']; // todo: check this field
        }
        $numeroTransaction = ""; // todo: check paybox
        $refAbonne = ""; // todo: check paybox TPE?

        az($res);


        $data = [
            'code_enseigne' => "1",
            'numero_commande' => "",
            'numero_dossier' => (int)$orderId,
            'code_client' => "", // todo: ask fixed value to SRD
            'argument_client' => "",
            'pave_adresse_facturation' => "",



            'pave_adresse_livraison' => "",
            'argument_adresse_livraison' => "todo", // todo: identifiant point relais
            'date_commande' => ExporterHelper::datetimeGetDate($res['date_commande']),
            'heure_minutes' => ExporterHelper::datetimeGetHoursMinutes($res['date_commande']),
            'mode_paiement' => "todo",
//            'type_cb' => ['C', 1, 0],
//            'numero_cb' => ['C', 19, 0],
//            'crypto' => ['C', 3, 0],
//            'date_validite' => ['C', 7, 0],
//            'numero_autorisation' => ['C', 6, 0],
            'support_commande' => "WEBFT",
            'montant_total_produit' => $montantTotalProduitHt,
            'frais_port' => $fraisPortHt,
            'frais_crt' => "",
            'frais_expedition' => "",
            'remise_support' => $reduction_ttc,
            'total_nap' => $totalNap,
            'total_tva' => $totalTva,
            'montant_paiements' => $montantPaiements,
            'solde' => $solde,
            'mode_expedition' => $res['id_carrier'],
            'top_nastva' => $topNasTva,
            'login' => "",
            'mot_de_passe' => "",
            'email' => $res['email'],
//            'donnees_cb_cryptees' => ['C', 32, 0],
            'numero_appel' => $numeroAppel,
            'numero_transaction' => $numeroTransaction,
            'ref_abonne' => $refAbonne,
            'acquereur' => "",
            'controle' => "#",
        ];

        $o->addFields($data);
        return $o;
    }

    public static function create()
    {
        return new self();
    }

}