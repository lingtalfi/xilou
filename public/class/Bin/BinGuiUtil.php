<?php


namespace Bin;

use Commande\CommandeUtil;
use Container\ContainerUtil;
use UniqueNameGenerator\Generator\ItemUniqueNameGenerator;

class BinGuiUtil
{


    public static function decorateUsedContainers(array &$usedContainers, $commandeId)
    {

        $ref = CommandeUtil::getReferenceById($commandeId);

        $containerName2Abbr = [
            'petit' => 'S',
            'moyen' => 'M',
            'grand' => 'L',
        ];
        // U: unknown

        $letter = 'A';

        $containerNames = ContainerUtil::getId2Labels();
        $gen = ItemUniqueNameGenerator::create()->setNamePool($containerNames);

        foreach ($usedContainers as $k => $usedContainer) {


            $type = (array_key_exists($usedContainer['name'], $containerName2Abbr)) ? $containerName2Abbr[$usedContainer['name']] : 'U';
            $suggestedName = $ref . '-' . $type . '-' . $letter++;
            $suggestedName = $gen->generate($suggestedName);


            $remainingVolume = $usedContainer['remainingVolume'];
            $remainingWeight = $usedContainer['remainingWeight'];
            $maxVolume = $usedContainer['maxVolume'];
            $maxWeight = $usedContainer['maxWeight'];

            $usedVolume = $usedContainer['maxVolume'] - $remainingVolume;
            $usedWeight = $usedContainer['maxWeight'] - $remainingWeight;

            $percentageVolumeUsed = ($usedVolume / $maxVolume) * 100;
            $percentageWeightUsed = ($usedWeight / $maxWeight) * 100;

            $usedContainers[$k]['volumeUsed'] = $usedVolume;
            $usedContainers[$k]['percentageVolumeUsed'] = $percentageVolumeUsed;
            $usedContainers[$k]['weightUsed'] = $usedWeight;
            $usedContainers[$k]['percentageWeightUsed'] = $percentageWeightUsed;
            $usedContainers[$k]['suggestedName'] = $suggestedName;

        }
    }

    public static function displayDecoratedUsedContainers(array $usedContainers, $commandeId)
    {
        ?>
        <style>
            .zilu-line-warning {
                background: #edb5b5;
            }
        </style>
        <div id="decorated-used-containers-json" class="hidden">
            <?php echo json_encode($usedContainers); ?>
        </div>
        <table>
            <tr>
                <th>Nom suggéré</th>
                <th>Type</th>
                <th>Volume max</th>
                <th>Poids max</th>
                <th>Volume utilisé</th>
                <th>% volume utilisé</th>
                <th>Poids utilisé</th>
                <th>% poids utilisé</th>
                <th>Liste items</th>
            </tr>
            <?php

            $r = 3;
            foreach ($usedContainers as $c):
                $class = '';
                if ($c['percentageVolumeUsed'] > 100 || $c['percentageWeightUsed'] > 100) {
                    $class = 'zilu-line-warning';
                }


                ?>
                <tr class="<?php echo $class; ?>">
                    <td><?php echo $c['suggestedName']; ?></td>
                    <td><?php echo $c['name']; ?></td>
                    <td><?php echo $c['maxVolume']; ?></td>
                    <td><?php echo $c['maxWeight']; ?></td>
                    <td><?php echo round($c['volumeUsed'], $r); ?></td>
                    <td><?php echo round($c['percentageVolumeUsed'], $r); ?></td>
                    <td><?php echo round($c['weightUsed'], $r); ?></td>
                    <td><?php echo round($c['percentageWeightUsed'], $r); ?></td>
                    <td><a href="#<?php echo $c['id']; ?>" class="container-item-link"
                           data-cid="<?php echo $commandeId; ?>"
                           data-coid="<?php echo $c['id']; ?>">Items</a>
                        <div class="hidden json-items"><?php echo json_encode($c['items']); ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }


    public static function displayContainerItems(array $items)
    {
        ?>
        <style>
            .zilu-line-even {
                background: #eee;
            }
        </style>
        <table>
            <tr>
                <th>Réf LF</th>
                <th>Nom</th>
                <th>Fournisseur</th>
                <th>Photo</th>
                <th>Poids</th>
                <th>Volume</th>
                <th>Quantité</th>
                <th>Prix</th>
            </tr>
            <?php


            if (count($items) > 0) {
                $item = $items[0];
                $ref = $item['commande'];

                if (false !== ($commandeId = CommandeUtil::getIdByReference($ref))) {


                    $i = 0;
                    foreach ($items as $c):
                        $class = '';
                        if (0 === ($i++ % 2)) {
                            $class = 'zilu-line-even';
                        }

                        ?>
                        <tr class="<?php echo $class; ?>">
                            <td><?php echo $c['reference_lf']; ?></td>
                            <td><?php echo $c['descr_fr']; ?></td>
                            <td><?php echo $c['fournisseur']; ?></td>
                            <td><img src="<?php echo $c['photo']; ?>"></td>
                            <td><?php echo $c['poids']; ?></td>
                            <td><?php echo $c['volume']; ?></td>
                            <td><?php echo $c['quantite']; ?></td>
                            <td><?php echo $c['prix']; ?></td>
                        </tr>
                    <?php endforeach;
                } else {
                    ?>
                    <div>Id not found</div>
                    <?php
                }
            }
            ?>
        </table>
        <?php
    }
}