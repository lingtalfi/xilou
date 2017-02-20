<?php


namespace Bin;

use CommandeHasArticle\CommandeHasArticleUtil;

class CommandeToBinHelper
{

    public static function distributeCommandeById($commandeId)
    {
        $ret = [];
        $details = CommandeHasArticleUtil::getCommandeDetails(1);
        a($details);




        return $ret;
    }


}