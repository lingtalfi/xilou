<?php


namespace Umail;


use Umail\Exception\UmailException;
use Umail\TemplateLoader\TemplateLoaderInterface;
use Umail\VarLoader\VarLoaderInterface;

class Umail implements UmailInterface
{

    /**
     * @var \Swift_Message $message
     */
    private $message;


    /**
     * htmlText and plainText are stored
     * for internal (gymnastic) purposes.
     */
    private $htmlText;
    private $plainText;

    /**
     * @var array $hooks , an array of:
     *              hookName => array of subscriber callbacks
     *
     */
    private $hooks;

    /**
     * @var string $templateName , the name of the template, if any
     */
    private $templateName;

    /**
     * @var TemplateLoaderInterface $templateLoader ,
     * the object responsible for resolving a template name into a template file path,
     * both the html and the plain text versions.
     */
    private $templateLoader;

    /**
     * @var array|VarLoaderInterface $vars ,
     *
     * Variables to use within a template (if the setTemplate method was used).
     * The $vars input can be of two forms:
     *
     * - array: useful if the template does not depend from the emails
     * - VarLoaderInterface: useful if the template depends from the emails
     *
     * See the setVars comments in the interface for more details.
     *
     */
    private $vars;

    /**
     * @var string(batch|merge), the send mode, default=batch
     *
     * In batch mode, each recipient sees only its own mail in the to field,
     * while in merge mode, each recipient sees all the recipients to which the email
     * has been sent.
     */
    private $emailSendMode;

    /**
     * @var string|array to
     * An internal variable used for the to gymnastic (to handle batch/merge mode).
     * Same format as SwiftMailer: http://swiftmailer.org/docs/messages.html
     */
    private $toRecipients;


    public function __construct()
    {
        /**
         * initialize swift mailer.
         * If Swift is not available, throw an exception
         */
        if (false === class_exists('Swift_SmtpTransport')) {
            throw new UmailException("Swift mailer not available");
        }
        $this->message = \Swift_Message::newInstance();
        $this->hooks = [];
        $this->toRecipients = [];
        $this->emailSendMode = 'batch';
    }

    public static function create()
    {
        return new static();
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    public function to($recipients)
    {
        if (is_string($recipients)) {
            $this->toRecipients[] = $recipients;
        } elseif (is_array($recipients)) {
            foreach ($recipients as $k => $v) {
                if (is_string($k)) {
                    $this->toRecipients[$k] = $v;
                } else {
                    $this->toRecipients[] = $v;
                }
            }
        }
        return $this;
    }

    public function bcc($recipients)
    {
        $this->message->addBcc($recipients);
        return $this;
    }

    public function cc($recipients)
    {
        $this->message->addCc($recipients);
        return $this;
    }

    public function from($recipients)
    {
        $this->message->addFrom($recipients);
        return $this;
    }

    public function subject($subject)
    {
        $this->message->setSubject($subject);
        return $this;
    }

    public function htmlBody($content)
    {
        $this->htmlText = $content;
        return $this;
    }

    public function plainBody($content)
    {
        $this->plainText = $content;
        return $this;
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

    public function setVars($vars)
    {
        $this->vars = $vars;
        return $this;
    }

    public function sendMode($mode)
    {
        $this->emailSendMode = $mode;
        return $this;
    }


    public function send()
    {
        $transport = $this->getTransport();
        $mailer = \Swift_Mailer::newInstance($transport);


        /**
         * filtering invalid emails
         */
        $invalidEmails = [];
        $toRecipients = $this->toRecipients;
        foreach ($toRecipients as $k => $v) {
            $name = null;
            if (is_string($k)) {
                $email = $k;
                $name = $v;
            } else {
                $email = $v;
            }
            if (false === \Swift_Validate::email($email)) {
                unset($toRecipients[$k]);
//                $this->hook("onInvalidEmail", $email);
            }
        }


        $this->message->setTo($toRecipients);

        if ('merge' === $this->emailSendMode) {


        } else {

        }


        /**
         * Set the body
         */
        if (null !== $this->templateName) {
            if (null !== $this->templateLoader) {

                /**
                 * Using a template
                 */
                $this->templateLoader->load($this->templateName);
                $htmlFile = $this->templateLoader->getHtmlFile();
                $plainFile = $this->templateLoader->getPlainFile();

                /**
                 * finding variables for the template
                 */
                $vars = [];
                if (null !== $this->vars) {
                    if (is_array($this->vars)) {
                        $vars = $this->vars;
                    } elseif ($this->vars instanceof VarLoaderInterface) {
                        $vars = $this->vars->getVariables($email);
                    }
                }


            } else {
                throw new UmailException("Cannot use template " . $this->templateName . " because no TemplateLoader is set.");
            }
        } else {
            /**
             * Default htmlBody/plainBody gymnastic
             */
            if (null === $this->htmlText && null !== $this->plainText) {
                $this->message->setBody($this->plainText);
            } elseif (null !== $this->htmlText && null === $this->plainText) {
                $this->message->setBody($this->htmlText);
            } elseif (null !== $this->htmlText && null !== $this->plainText) {
                $this->message->setBody($this->htmlText, 'text/html');
                $this->message->addPart($this->plainText, 'text/plain');
            }
        }


//        $this->hook('onMessagePreparedAfter', [$mailer]); // wait concrete need to uncomment, because you could pass different parameters


        /**
         * Sending the email,
         * batch mode, or merge mode
         */
        return $mailer->send($this->message);
        if ('merge' === $this->emailSendMode) {
            return $mailer->send($this->message);
        } else {
            return $mailer->send($this->message);
        }
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    public function register($hookName, $callback)
    {
        $this->hooks[$hookName][] = $callback;
        return $this;
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    /**
     * @return \Swift_Transport, a swift transport instance
     */
    protected function getTransport()
    {
        return \Swift_MailTransport::newInstance();
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    private function hook($hookName, $params)
    {
        if (array_key_exists($hookName, $this->hooks)) {
            foreach ($this->hooks[$hookName] as $cb) {
                call_user_func($cb, $params);
            }
        }
    }


}