<?php


namespace Umail;


use Umail\Exception\UmailException;
use Umail\TemplateLoader\TemplateLoaderInterface;


/**
 * This class serves as the documentation cheatsheet for the Umail class.
 * Umail needs SwiftMailer to be available before it can be used.
 *
 *
 *
 * Note:
 * if you want to get the invalid emails that have been rejected,
 * you have to hope that your concrete class provides you with hooks to do so:
 * there is no such capability inherent to this interface.
 * This is the desired design.
 *
 *
 */
interface UmailInterface
{


    /**
     * Create the base instance and returns it,
     * it should always be the first method that you call.
     *
     *
     * @return UmailInterface
     * @throws UmailException if SwiftMailer library is not available
     */
    public static function create();


    /**
     * Set the recipients of the email.
     *
     * $recipients:
     * recipient(s) of the email.
     * If it's a string, it's the email of the recipient.
     * If it's an array, it's an array of recipients.
     *
     * $batchMode: bool, whether to use the batchMode or not.
     * If the batch mode is used (default), every recipient receives her own email,
     * a VarLoader object can be used, and the "to" field only contains the recipient address.
     *
     * If the batch mode is NOT used, then every recipient receives the SAME email,
     * which means that the VarLoader object is useless (see more info in the comments of the setVarLoader method),
     * and the "to" field contains the email addresses of every recipient (i.e. everybody knows who the mail was sent to).
     *
     */
    public function to($recipients, $batchMode = true);

    public function bcc($recipients);

    public function cc($recipients);

    public function from($recipients);

    public function subject($subject);

    /**
     * htmlBody and plainBody set the content of the email,
     * they are only used if no template is set (see the setTemplate method).
     */
    public function htmlBody($content);

    public function plainBody($content);

    /**
     * Sets a template.
     *
     * If the template is set, it will be used instead of the content set by
     * the htmlBody and plainBody methods.
     *
     * This method sets the template name, from which the template content (for both html and/or plain version)
     * can be guessed (with the help of the TemplateLoader object).
     *
     * It's a template for the mail body only (not a template for the mail subject).
     *
     */
    public function setTemplate($templateName);

    /**
     * Set the template loader object, which is responsible for resolving
     * a template name into a template content for both the html and
     * the plain text versions.
     */
    public function setTemplateLoader(TemplateLoaderInterface $loader);


    /**
     * Variables
     * --------------
     *
     * Variables can be injected in the body and or the subject of an email.
     * You can use variables no matter where the body comes from (htmlBody method,
     * or setTemplate method).
     *
     * This method sets the variables to use.
     *
     * There are two types of variables:
     *
     * - common variables: they are the same for every recipient
     * - email variables: they depend on the recipient's email address
     *
     * Both types are in the form of an array of variable => value.
     *
     *
     * @param array $commonVars : an array of common variables
     * @param callable $emailVarsCb : a callable which takes an email address as input,
     *                  and returns an array of corresponding variables.
     *
     */
    public function setVars(array $commonVars, $emailVarsCb = null);

    /**
     * A variable injected into a body is called "variable reference".
     *
     * The func argument is a callable that takes a variable as input,
     * and returns a variable reference as the output.
     *
     * Typically, a variable reference is like a variable, but with curly braces around.
     * For instance {myVariable} could be the variable reference for the variable myVariable.
     *
     */
    public function setVarReferenceWrapper($func);


    /**
     * Send the prepared email to the prepared recipients,
     * and return the number of emails successfully sent.
     *
     * @return int
     */
    public function send();


}