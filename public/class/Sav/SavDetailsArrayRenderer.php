<?php


namespace Sav;


use QuickPdo\QuickPdo;
use Util\ArrayRenderer;

class SavDetailsArrayRenderer extends ArrayRenderer
{
    public function prepareBySavId($savId)
    {
        $q = '
select
s.id,
s.fournisseur,
s.reference_lf,
s.produit,
s.livre_le,
s.quantite,
s.prix,
s.nb_produits_defec,
s.date_notif,
s.demande_remboursement,
s.montant_rembourse,
s.pourcentage_rembourse,
s.date_remboursement,
s.forme,
s.statut,
s.photo,
s.avancement
from zilu.sav s
where s.id=' . $savId;
        if (false !== ($res = QuickPdo::fetch($q))) {

            if ('' !== $res['photo']) {
                $value = $res['photo'];
                $thumb = htmlspecialchars($value);
                $big = dirname(dirname($value)) . '/' . basename($value);
                $res['photo'] = '<a href="' . $big . '" data-lightbox="image-1"><img src="' . $thumb . '"></a>';
            }

            $this->setValues($res);
        }
        return $this;
    }

}