<?php


namespace Umail\TemplateLoader;


class TemplateLoader implements TemplateLoaderInterface
{

    /**
     * @var string $dir ,
     * the root dir containing all the templates.
     *
     * A template file's path is actually the root dir plus a relative path.
     *
     * By default, it assumes that there is a "mails" directory at the
     * root of your application.
     *
     */
    private $dir;

    /**
     * htmlPath, plainPath are locations to the actual template files
     */
    private $htmlPath;
    private $plainPath;


    public function __construct()
    {
        $this->dir = __DIR__ . "/../../../mails";
    }

    public function setDir($d)
    {
        $this->dir = $d;
        return $this;
    }

    /**
     * Triggers the resolution of the given $templateName
     * to an html file, a plain file, or both.
     *
     * Then to access the files paths use the getHtmlFile
     * and/or getPlainFile methods.
     */
    public function load($templateName)
    {
        $this->htmlPath = $this->dir . "/" . $this->getHtmlRelativePath($templateName);
        $this->plainPath = $this->dir . "/" . $this->getPlainRelativePath($templateName);
        return $this;
    }


    /**
     * Returns the html file path, or null.
     * Be sure to call the load method first.
     */
    public function getHtmlFile()
    {
        return $this->htmlPath;
    }

    /**
     * Returns the plain/text file path, or null.
     * Be sure to call the load method first.
     */
    public function getPlainFile()
    {
        return $this->plainPath;
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    protected function getHtmlRelativePath($templateName)
    {
        return $templateName . '/' . $templateName . '.html.tpl';
    }

    protected function getPlainRelativePath($templateName)
    {
        return $templateName . '/' . $templateName . '.plain.tpl';
    }

}