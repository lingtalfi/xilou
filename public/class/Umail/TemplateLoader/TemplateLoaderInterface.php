<?php


namespace Umail\TemplateLoader;


interface TemplateLoaderInterface
{


    /**
     * Triggers the resolution of the given $templateName
     * to an html file, a plain file, or both.
     *
     * Then to access the files paths use the getHtmlFile
     * and/or getPlainFile methods.
     */
    public function load($templateName);


    /**
     * Returns the html file path, or null.
     * Be sure to call the load method first.
     */
    public function getHtmlFile();

    /**
     * Returns the plain/text file path, or null.
     * Be sure to call the load method first.
     */
    public function getPlainFile();

}