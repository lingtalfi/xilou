<?php


namespace SrdExport\Exporter;


use QuickPdo\QuickPdo;
use SrdExport\Exporter\Helper\ExporterHelper;

class FluxDetailCommandeExporter extends CsvExporter
{
    public function __construct()
    {
        parent::__construct();
        $this->setSettings([
            'code_enseigne' => ['C', 1, 0],
            'numero_commande' => ['I', 7, 0],
            'numero_dossier' => ['C', 15, 0],
            'numero_ligne' => ['I', 4, 0],
            'code_offre' => ['C', 5, 0],
            'reference' => ['C', 16, 0],
            'quantite_commandee' => ['I', 10, 0],
            'top_cadeau' => ['L', 1, 0],
            'prix_unitaire_initial' => ['N', 12, 2],
            'montant_remise' => ['N', 12, 2],
            'type_remise' => ['N', 1, 0],
            'prix_unitaire_remise' => ['N', 12, 2],
            'code_tva' => ['C', 2, 0],
            'montant_tva' => ['N', 12, 2],
            'montant_total_ligne' => ['N', 12, 2],
            'controle' => ['C', 1, 0],
        ]);
    }


    public static function createByOrderId($orderId)
    {

        $o = self::create();
        $res = QuickPdo::fetchAll('
select
 
id_tax_rules_group,
product_reference as reference,
product_quantity as quantity,
product_quantity,
product_price,
total_price_tax_incl,
total_price_tax_excl,
reduction_percent,
reduction_amount
 
from
ps_order_detail
where id_order=' . $orderId . '
');

        $i = 1;
        foreach ($res as $item) {

            $topCadeau = 0; // todo: voir quelle solution est utilisée pour servir des produits offerts (cadeaux)


            $montantRemise = $item['product_price'] - $item['total_price_tax_excl'];
            $typeRemise = "";
            if (0 !== (int)$item['reduction_percent']) {
                $typeRemise = "1";
            } elseif (0 !== (int)$item['reduction_amount']) {
                $typeRemise = "2";
            } else {
                // todo: demander réduction prix barré ?
            }


            $prixUnitRemiseHt = $item['total_price_tax_excl'];


            $codeTva = $item['id_tax_rules_group'];
            $montantTva = $item['total_price_tax_incl'] - $item['total_price_tax_excl'];
            $totalLigne = $item['total_price_tax_excl'] * $item['product_quantity'];


            $data = [
                'code_enseigne' => "1",
                'numero_commande' => $orderId,
                'numero_dossier' => "",
                'numero_ligne' => $i++,
                'code_offre' => "",
                'reference' => $item['reference'],
                'quantite_commandee' => $item['quantity'],
                'top_cadeau' => $topCadeau,
                'prix_unitaire_initial' => $item['product_price'],
                'montant_remise' => $montantRemise,
                'type_remise' => $typeRemise,
                'prix_unitaire_remise' => $prixUnitRemiseHt,
                'code_tva' => $codeTva,
                'montant_tva' => $montantTva,
                'montant_total_ligne' => $totalLigne,
                'controle' => "#",
            ];
            $o->addFields($data);
        }


        return $o;
    }

    public static function create()
    {
        return new self();
    }

}