<?php


namespace SrdExport\Exporter;


use QuickPdo\QuickPdo;
use SrdExport\Exporter\Helper\ExporterHelper;

class FluxCommentaireCommandeExporter extends CsvExporter
{
    public function __construct()
    {
        parent::__construct();
        $this->setSettings([
            'code_enseigne' => ['C', 1, 0],
            'numero_dossier' => ['C', 15, 0],
            'numero_ligne' => ['I', 4, 0],
            'commentaire' => ['C', 996, 0],
            'controle' => ['C', 1, 0],
        ]);
    }


    public static function createByHookParams(array $params)
    {
        $o = self::create();
        $object = $params['object'];
        $idLigne = $object->id;
        $message = $object->message;

        $data = [
            'code_enseigne' => "1",
            'numero_dossier' => "",
            'numero_ligne' => $idLigne,
            'commentaire' => $message,
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