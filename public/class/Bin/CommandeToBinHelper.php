<?php


namespace Bin;

use Bin\Exception\WeightOverloadException;
use Bin\Swapper\NegativeWeightSwapper;
use CommandeHasArticle\CommandeHasArticleUtil;
use TypeContainer\TypeContainer;

class CommandeToBinHelper
{

    public static function distributeCommandeById($commandeId)
    {
        $ret = [];
        $details = CommandeHasArticleUtil::getCommandeDetails($commandeId);
//        a($details);

        $containers = TypeContainer::getTypeContainerDetails();

        $o = LingSwapBinUtil::create();
        $o->setKeyContainerName('label');
        $o->setKeyContainerWeight('poids_max');
        $o->setKeyContainerVolume('volume_max');
        $o->setKeyItemVolume('volume');
        $o->setKeyItemWeight('poids');
        $o->setKeyItemId('aid');

        $o->setContainers($containers);
        $o->setItems($details);
        $containers = $o->getContainersToUse();
        $usedContainers = $o->bestFit($containers);


        $swapper = NegativeWeightSwapper::create()->setNbTries(3);
        while (false !== $tmpUsedContainers = $swapper->swap($usedContainers)) {
            $usedContainers = $tmpUsedContainers;
        }


        $so = new SummaryInfoTool();
        $so->keyContainerName = $o->getKeyContainerName();
        $so->keyContainerWeight = $o->getKeyContainerWeight();
        $so->keyContainerVolume = $o->getKeyContainerVolume();
        $so->keyItemId = $o->getKeyItemId();
        $so->keyItemVolume = $o->getKeyItemVolume();
        $so->keyItemWeight = $o->getKeyItemWeight();
        $summary = $so->getSummaryInfo($details, $usedContainers, $containers);


        if ($summary['containersWeightNegativeSum'] < 0 || $summary['containersVolumeNegativeSum'] < 0) {
            $e = new  WeightOverloadException("Warning: some containers have been overloaded");
            $e->usedContainers = $usedContainers;
            throw $e;
//            $usedContainers = $o->safeFit($containers);
        }
        return $usedContainers;
    }


}