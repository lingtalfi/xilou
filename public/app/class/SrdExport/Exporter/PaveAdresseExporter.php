<?php


namespace SrdExport\Exporter;


use QuickPdo\QuickPdo;

class PaveAdresseExporter extends CsvExporter
{
    public function __construct()
    {
        parent::__construct();
        $this->setSettings([
            'code_civilite' => ['C', 3, 0],
            'nom' => ['C', 32, 0],
            'prenom' => ['C', 32, 0],
            'complement_1' => ['C', 32, 0],
            'complement_2' => ['C', 32, 0],
            'numero_voie' => ['C', 10, 0],
            'nom_voie' => ['C', 32, 0],
            'cp' => ['C', 10, 0],
            'ville' => ['C', 38, 0],
            'code_pays' => ['C', 3, 0],
            'code_etat' => ['C', 8, 0],
            'telephone' => ['C', 18, 0],
            'mobile' => ['C', 18, 0],
            'email' => ['C', 48, 0],
            'date_naissance' => ['D', 0, 0],
            'code_langue' => ['C', 2, 0],
            'date_modification' => ['D', 0, 0],
        ]);
    }


    public static function createByCustomerId($customerId)
    {

        $o = self::create();

        $res = QuickPdo::fetch('
select
 
c.id_gender as code_civilite,
c.lastname as nom,
c.firstname as prenom,
c.birthday as date_naissance,
c.id_lang as code_langue,
c.email as email,
a.address1 as complement_1,
a.address2 as complement_2,
a.postcode as cp,
a.city as ville,
a.id_country as code_pays,
a.id_state as code_etat,
a.phone as telephone1,
a.phone_mobile as telephone2,
a.date_upd as date_modification

from
ps_customer c
inner join ps_address a on a.id_customer=c.id_customer
where c.id_customer=' . $customerId . '
');


        $res['numero_voie'] = '';
        $res['nom_voie'] = '';

        if (preg_match('!([0-9]+)[^a-zA-Z]*([a-zA-Z]+.*)!', $res['complement_1'], $match)) {
            $res['numero_voie'] = $match[1];
            $res['nom_voie'] = trim($match[2]);
        }

        $data = [
            'code_civilite' => $res['code_civilite'],
            'nom' => $res['nom'],
            'prenom' => $res['prenom'],
            'complement_1' => $res['complement_1'],
            'complement_2' => $res['complement_2'],
            'numero_voie' => $res['numero_voie'],
            'nom_voie' => $res['nom_voie'],
            'cp' => $res['cp'],
            'ville' => $res['ville'],
            'code_pays' => $res['code_pays'],
            'code_etat' => $res['code_etat'],
            'telephone' => $res['telephone1'],
            'mobile' => $res['telephone2'],
            'email' => $res['email'],
            'date_naissance' => $res['date_naissance'],
            'code_langue' => $res['code_langue'],
            'date_modification' => $res['date_modification'],
        ];

        $o->addFields($data);
        return $o;
    }

    public static function create()
    {
        return new self();
    }

}