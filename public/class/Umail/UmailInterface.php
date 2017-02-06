<?php


namespace Umail;


use Umail\Exception\UmailException;
use Umail\TemplateLoader\TemplateLoaderInterface;


/**
 * This class serves as the documentation cheatsheet for the Umail class.
 * Umail needs SwiftMailer to be available before it can be used.
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
     * See sendMode method comments for more details.
     *
     */
    public function to($recipients);

    public function bcc($recipients);

    public function cc($recipients);

    public function from($recipients);

    public function subject($subject);

    /**
     * htmlBody and plainBody set the content of the email,
     * they are overridden when the setTemplate method is used.
     */
    public function htmlBody($content);

    public function plainBody($content);

    /**
     * Sets the template name, from
     * which the real path to the html template file, and/or the plain text template file
     * can be guessed (with the help of the TemplateLoader object).
     *
     * It's a template for the mail body only (not a template for the mail subject).
     *
     * If the template is set, it will be used instead of the content set by
     * the htmlBody and plainBody methods.
     *
     * A template can use variables, which are set indirectly via the
     * setVars method.
     *
     */
    public function setTemplate($templateName);

    /**
     * Set the template loader object, which is responsible for resolving
     * a template name into a template file path, both the html and
     * the plain text versions.
     */
    public function setTemplateLoader(TemplateLoaderInterface $loader);


    /**
     * Sets the variables to use within a template (if the setTemplate method was used).
     * The $vars input can be of two forms:
     *
     * - array: use this form if the template does not depend on the recipient's email address
     *              a simple str_replace php mechanism will be used.
     * - VarLoaderInterface: use this form if the template depends on the recipient's email address
     *              Then the email address will be passed to the VarLoaderInterface, which role
     *              is to return the corresponding variables.
     *
     *
     * Variables are applied to both the subject and the body of the email.
     *
     * If it's an array, the keys are the variable names,
     * and the values are the variable values.
     *
     *
     */
    public function setVars($vars);


    /**
     * $mode, string: batch|merge
     *
     * There are two ways of sending an email to a recipient.
     * In batch mode (default), every recipient receives an email with its own
     * email (or name) in the "to" field (of the mail box software which receives the email),
     * whereas in merge mode, the "to" recipients are all merged together
     * and visible in the "to" field.
     */
    public function sendMode($mode);


    /**
     * Send the prepared email to the prepared recipients,
     * and return the number of emails successfully sent.
     *
     * @return int
     */
    public function send();


}