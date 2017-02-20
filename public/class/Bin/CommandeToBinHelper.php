<?php


namespace Bin;

use Bin\Swapper\ItemsSelector\BestFitItemsSelector;
use Bin\Swapper\ItemsSelector\ItemsSelector;
use Bin\Swapper\Swapper;
use CommandeHasArticle\CommandeHasArticleUtil;
use Container\ContainerUtil;
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


        $so = new SummaryInfoTool();
        $so->keyContainerName = $o->getKeyContainerName();
        $so->keyContainerWeight = $o->getKeyContainerWeight();
        $so->keyContainerVolume = $o->getKeyContainerVolume();
        $so->keyItemId = $o->getKeyItemId();
        $so->keyItemVolume = $o->getKeyItemVolume();
        $so->keyItemWeight = $o->getKeyItemWeight();
        $summary = $so->getSummaryInfo($details, $usedContainers, $containers);


        a($summary);
        a($usedContainers);


        $swapper = Swapper::create()
            ->setItemSelector(BestFitItemsSelector::create()->setSummary($summary));
        $swapper->swap($usedContainers);



        return $usedContainers;
    }


}