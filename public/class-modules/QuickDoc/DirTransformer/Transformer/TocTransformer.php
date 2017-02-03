<?php


namespace QuickDoc\DirTransformer\Transformer;

use DirTransformer\Transformer\TransformerInterface;
use QuickDoc\Util\TocUtil;

class TocTransformer implements TransformerInterface
{


    public function transform(&$content)
    {
        if (TocUtil::hasToc($content)) {
            $content = TocUtil::getTocSymbolReplacedContent($content);
        }
    }

}