<?php


namespace DbTransition;

use QuickPdo\QuickPdo;

class TypeContainer{

    public static function create(){
        $typeContainerTable = 'type_container';
        if (false !== $id = (QuickPdo::insert($typeContainerTable, [
                'label' => "petit",
                'poids_max' => "10000",
                'volume_max' => "10000",
            ]))
        ) {
            $typeContainerIds[] = $id;
        }
        if (false !== $id = (QuickPdo::insert($typeContainerTable, [
                'label' => "moyen",
                'poids_max' => "20000",
                'volume_max' => "20000",
            ]))
        ) {
            $typeContainerIds[] = $id;
        }
        if (false !== $id = (QuickPdo::insert($typeContainerTable, [
                'label' => "grand",
                'poids_max' => "40000",
                'volume_max' => "40000",
            ]))
        ) {
            $typeContainerIds[] = $id;
        }
    }

}