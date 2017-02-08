<?php


namespace Updf;


use Updf\Component\ComponentInterface;


/**
 * This class requires tcpdf to be loaded.
 *          require_once __DIR__ . "/TCPDF/tcpdf.php";
 */
class Updf
{

    private $elements;
    private $tcpdf;

    public function __construct()
    {
        $this->elements = [];
        $this->tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $this->tcpdf->SetFont('dejavusans', '', 10);
        $this->tcpdf->AddPage();
    }

    public static function create()
    {
        return new static();
    }


    /**
     * @param ComponentInterface|string $element
     *              An element can be either a template or a component.
     *
     *
     *
     *
     *              In both cases, the goal is to obtain a template content and
     *              some variables.
     *              The injection of the variables into the template content
     *              is then done by THIS class.
     *
     *
     *              If a template is passed, it's a string representing the template name,
     *              and the vars argument (second argument) is used.
     *              The template name is resolved to a template content
     *              via a TemplateLoader object, which is set apart.
     *
     *
     *              If it's a component, a ComponentInterface object is passed,
     *              and the vars argument is not used, since the component object
     *              creates its own variables.
     *              The component object also returns a template name,
     *              which is in turn resolved into template content using the same
     *              TemplateLoader object as the one used for the template version.
     *
     *
     *
     * @param array|null $vars
     * @return $this
     */
    public function addElement($element, array $vars = null)
    {
        $this->elements[] = [$element, $vars];
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
        foreach ($this->elements as $elementInfo) {


            /**
             * Get the template's content and related variables
             */
            $html = '';
            $vars = [];
            if ($elementInfo[0] instanceof ComponentInterface) {
                /**
                 * @var ComponentInterface $component
                 */
                $component = $elementInfo[0];
                $html = $component->getTemplateContent();
                $vars = $component->getTemplateVars();
            } else {
                list($html, $vars) = $elementInfo;
            }


            /**
             * Inject variables into the template
             */
            $this->injectVariables($html, $vars);


            /**
             * Write the html
             */
            $this->tcpdf->writeHTML($html, true, false, true, false, '');


        }

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
     * By default, we use a simple {tag} replacement system.
     *
     */
    protected function injectVariables(&$html, array $vars)
    {
        /**
         * Inject variables into the template
         */
        $varsKeys = array_map(function ($v) {
            return '{' . $v . '}';
        }, array_keys($vars));
        $varsValues = array_values($vars);
        $html = str_replace($varsKeys, $varsValues, $html);
    }
}