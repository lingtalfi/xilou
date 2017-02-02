<?php


namespace Layout;


use ToolsLeftMenuSection\ToolsLeftMenuSectionModule;

class LayoutServices
{

    /**
     * Owned by:
     * - class/Layout
     */
    public static function displayLeftMenuBlocks()
   {
        ToolsLeftMenuSectionModule::displayLeftMenuBlocks();
        \Crud\CrudModule::displayLeftMenuBlocks();
        \FrontOne\FrontOneModule::displayLeftMenuBlocks();
    }


    /**
     * Owned by:
     * - class/Layout
     */
    public static function displayTopBar()
    {
        \Lang\LangModule::displayTopBar();
    }

}