<?php


namespace Updf\Component;

use Updf\Theme\Theme;
use Updf\Theme\ThemeInterface;
use Updf\Util\UpdfUtil;


/**
 * This class is my recommended implementation for the ComponentInterface
 * (at least it covers my needs).
 *
 *
 * It has a few built-in features explained below.
 *
 *
 *
 * text vars
 * ==============
 *
 * We can differentiate 3 types of template variables:
 *
 * - theme variables, variables that belong to the theme, prefixed with "theme_"
 * - text variables, variables that belong to the template:
 *                      they only change with the lang, prefixed with "text_"
 * - dynamic variables, the default dynamic variables of the template.
 *
 *
 * To present them, imagine this fictitious example:
 *
 * <img src="{theme_logo}">
 * <table>
 *      <tr>
 *          <th>{text_price}</th>
 *          <th>{text_quantity}</th>
 *      </tr>
 *      <tr>
 *          <td>{price}</td>
 *          <td>{quantity}</td>
 *      </tr>
 * </table>
 *
 * So do you understand the role/goal of a text variable now?
 * (hopefully you do)
 *
 *
 * The text variable are created by the pdf component template author.
 * As any text, it depends on the language, and this class has a built-in mechanism
 * to help you handle this internationalization problem.
 *
 *
 * How does this work?
 * --------------------
 * Next to the class, create a lang directory, which contains a lang specific directory,
 * for instance lang/en for english, or lang/fr for french.
 *
 * In that directory, create a php file with the same name as the class, but using the
 * snake_case instead of the class's default CamelCase.
 *
 * This file should contain an array referenced by a variable $defs, which keys
 * are the messages to translate, and which values are the translated values
 * for that language.
 *
 * To help understand this, let's picture it with a fictitious tree example:
 *
 * - MyPackage
 * ----- MyModule
 * ----- lang
 * --------- en
 * ------------- my_module.php     # contains the text vars for english
 * --------- fr
 * ------------- my_module.php      # contains the text vars for frence
 * --------- ...
 *
 * And if you open the MyPackage/MyModule/lang/fr/my_module.php file for instance,
 * you might find something like this inside:
 *
 * ```php
 * <?php
 *
 * $defs = [
 *      'text_invoice' => 'facture', // translation of invoice in french
 *      ...
 * ];
 * ```
 *
 * Note: don't forget the "text_" prefix if you want to respect that convention.
 *
 *
 * Now on the other side of the chain we have the template.
 * The template contains the text vars references.
 * A text var reference, by convention, starts with the "text_" prefix.
 * Here is a fictitious template example:
 *
 * ```html
 * <h1>{text_invoice}</h1>
 * <p>{user_comment}</p>
 * ```
 *
 * That's all you need to make this internationalization mechanism work:
 * - use the "text_" prefix in your templates
 * - create a lang directory next to the called component class
 *
 *
 * Choosing the lang
 * ----------------------
 * Last but not least: the chosen lang is brought by the Theme object,
 * which is automatically injected by the Updf class into the components.
 *
 * So if you want to change the lang, you need to call the Theme object's setLang method.
 *
 *
 *
 * public properties vars
 * ==========================
 *
 * Since I'm very lazy, I wanted to be able to create a component variables
 * using only public properties.
 *
 * For instance:
 *
 * class MyComponent{
 *      public $user_name = "Michel";
 *      public $user_password = "MichMich";
 *      public $user_avatar = "/path/to/michel.jpg";
 * }
 *
 * So this mechanism has been implemented and you can use it.
 * Notice that by convention, all public properties use snake_case.
 *
 *
 *
 *
 */
abstract class AbstractComponent implements ComponentInterface
{

    protected $vars;

    /**
     * @var ThemeInterface $theme
     */
    protected $theme;

    public function __construct()
    {
        $this->vars = [];
    }

    public static function create()
    {
        return new static();
    }

    public function getTemplateName()
    {
        $p = explode('\\', get_called_class());
        return array_pop($p);
    }


    public function getTemplateVars()
    {
        /**
         * get text vars
         */
        $langVars = $this->getTextVars();

        /**
         * get public vars
         */
        $publicPropsVars = $this->getPublicPropsVars();
        $this->vars = array_merge($this->vars, $langVars, $publicPropsVars);
        return $this->vars;
    }

    public function setTheme(ThemeInterface $theme)
    {
        $this->theme = $theme;
        return $this;
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    protected function getTextVars()
    {
        $lang = $this->theme->getLang();
        $r = new \ReflectionClass($this);
        $d = dirname($r->getFileName()) . '/lang/' . $lang;
        $file = $d . '/' . UpdfUtil::camelToSuperSnake($r->getShortName()) . '.php';
        $defs = [];
        if (file_exists($file)) {
            include $file;
        }
        return $defs;
    }

    protected function getPublicPropsVars()
    {

        $ret = [];
        $r = new \ReflectionClass($this);
        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $ret[$property->getName()] = $property->getValue($this);
        }
        return $ret;
    }
}