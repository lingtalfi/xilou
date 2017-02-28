<?php


namespace QuickDoc\DirTransformer\Transformer;

use DirTransformer\Transformer\TransformerInterface;
use QuickDoc\Util\TreeUtil;

class TreeTransformer implements TransformerInterface
{
    private $srcDir;
    private $prefix;

    public function __construct($srcDir, $prefix)
    {
        $this->srcDir = $srcDir;
        $this->prefix = $prefix;
    }

    public function transform(&$content)
    {
        if (TreeUtil::hasTree($content)) {
            $content = TreeUtil::getTreeSymbolReplacedContent($this->srcDir, $this->prefix, $content);
        }
    }

}