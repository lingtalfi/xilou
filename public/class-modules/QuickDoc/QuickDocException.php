<?php


namespace QuickDoc;


/**
 * Throw this exception to indicate a quickDoc logic error.
 * It will not break the whole layout, but just replace the quickDoc body content
 * with a goofy alert.
 */
class QuickDocException extends \Exception
{

}