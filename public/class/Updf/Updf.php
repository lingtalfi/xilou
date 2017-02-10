<?php


namespace Updf;


use Updf\Exception\UpdfException;
use Updf\Model\ModelInterface;
use Updf\TemplateLoader\TemplateLoader;
use Updf\TemplateLoader\TemplateLoaderInterface;


/**
 * This class requires tcpdf to be loaded.
 *          require_once __DIR__ . "/TCPDF/tcpdf.php";
 */
class Updf
{
    private $tcpdf;
    protected $templateName;
    protected $templateLoader;
    protected $vars;
    protected $model;

    public function __construct()
    {
        $this->vars = [];
        $this->tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $this->tcpdf->SetFont('dejavusans', '', 10);
        $this->tcpdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $this->tcpdf->AddPage();
    }

    public static function create()
    {
        return new static();
    }

    public function setTemplate($templateName)
    {
        $this->templateName = $templateName;
        return $this;
    }

    public function setTemplateLoader(TemplateLoaderInterface $loader)
    {
        $this->templateLoader = $loader;
        return $this;
    }

    public function setVariables(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
        return $this;
    }


    /**
     * @param null|string $type
     *          Defines how the pdf should be rendered.
     *          The default value is null, which means
     *          the pdf is rendered in the browser (using any pdf plugin
     *          the browser has).
     *
     *          If the $type is a string, then it's the path of the pdf file to create.
     *
     *
     *
     *
     *
     */
    public function render($type = null)
    {


        /**
         * prepare the variables
         */
        $vars = $this->vars;
        if ($this->model instanceof ModelInterface) {
            $vars = array_merge($this->model->getVariables(), $vars);
        }


        /**
         * Interpret the content and inject the variables
         */
        $html = $this->renderTemplate($this->templateName, $vars);


        /**
         * Write the html
         */
        $this->tcpdf->writeHTML($html, true, 0, true);


        /**
         * Output the pdf
         */
        $dest = 'I';
        $fileName = 'output.pdf';
        if (is_string($type)) {
            $fileName = $type;
            $dest = 'F';
        }
        $this->tcpdf->Output($fileName, $dest);
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/


    /**
     * This method is responsible for injecting the variables
     * into the template (and thus resolving them).
     *
     * Inside a template, you can call other templates using {this} notation,
     * with the template name inside the curly braces (for instance {invoice.addresses}).
     *
     */
    protected function renderTemplate($templateName, array $vars)
    {

        /**
         * get the uninterpreted content
         */
        $content = '';

        $context = null; // not used for now
        $loader = $this->getTemplateLoader();
        if (false !== ($_content = $loader->load($templateName, $context))) {
            $content = $_content;
        } else {
            throw new UpdfException("Couldn't load the template content for " . $templateName);
        }


        if (false !== ($path = $this->tmpFile($content))) {
            /**
             * Prepare vars
             */
            $varKeys = [];
            $varValues = [];
            foreach ($vars as $k => $v) {
                if (!is_array($v)) {
                    $varsKeys[] = '__' . $k . '__';
                    $varsValues[] = nl2br($v);
                }
            }


            /**
             * Convert all variables accessible as objects.
             * (i.e. $v->my_var withing the template)
             *
             */
            $v = json_decode(json_encode($vars), false);

            /**
             * First interpret the template's php if any
             */
            ob_start();
            include $path;
            $content = ob_get_clean();


            /**
             * Then inject variables into the template
             */
            $content = str_replace($varsKeys, $varsValues, $content);

            $content = preg_replace_callback('!{([a-zA-Z_][a-zA-Z0-9._-]*)}!', function ($m) use ($vars) {
                $tplName = $m[1];
                return $this->renderTemplate($tplName, $vars);
            }, $content);


            return $content;


        } else {
            throw new UpdfException("Cannot create the temporary file to create content");
        }
    }


    /**
     * @return TemplateLoaderInterface
     */
    protected function getTemplateLoader()
    {
        if (null === $this->templateLoader) {
            $this->templateLoader = new TemplateLoader();
        }
        return $this->templateLoader;
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    private function tmpFile($content)
    {
        $tmpfname = tempnam("/tmp/updf", "FOO");
        file_put_contents($tmpfname, $content);
        return $tmpfname;
    }


}