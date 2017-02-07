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

    private $commonVars;
    private $emailVarsCb;
    private $isBatchMode;

    /**
     * @var string|array to
     * An internal variable used for the to gymnastic (to handle batch/merge mode).
     * Same format as SwiftMailer: http://swiftmailer.org/docs/messages.html
     */
    private $toRecipients;

    /**
     * @var \Closure callback for wrapping the variable references
     * (pou -> {pou} for instance)
     */
    private $varRefWrapper;
    private $_subject;

    /**
     * $file, $fileName: file attachment related vars
     */
    private $file;
    private $fileName;
    private $fileMimeType;


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
        $this->commonVars = [];
        $this->isBatchMode = true;
        $this->varRefWrapper = function ($var) {
            return '{' . $var . '}';
        };
    }

    public static function create()
    {
        return new static();
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    public function getMailer()
    {
        $transport = $this->getTransport();
        return \Swift_Mailer::newInstance($transport);
    }


    public function to($recipients, $batchMode = true)
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
        $this->isBatchMode = $batchMode;
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

    public function replyTo($recipient)
    {
        $this->message->setReplyTo($recipient);
        return $this;
    }

    public function subject($subject)
    {
        $this->_subject = $subject;
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

    public function setVars(array $commonVars, $emailVarsCb = null)
    {
        $this->commonVars = $commonVars;
        $this->emailVarsCb = $emailVarsCb;
        return $this;
    }

    public function setVarReferenceWrapper($func)
    {
        $this->varRefWrapper = $func;
        return $this;
    }

    public function attachFile($file, $fileName = null, $mimeType = null)
    {
        $this->file = $file;
        $this->fileName = $fileName;
        $this->fileMimeType = $mimeType;
        return $this;
    }


    public function send()
    {
        $mailer = $this->getMailer();


        list($htmlContent, $plainContent) = $this->prepareBody();
        $subjectContent = $this->_subject;


        $totalSent = 0;
        if (true === $this->isBatchMode) {
            /**
             * batch mode, each recipient receives its own mail copy,
             * and the "to" field only contains the recipient address
             */
            foreach ($this->toRecipients as $k => $v) {

                try {
                    /**
                     * filtering invalid emails
                     */
                    $name = null;
                    if (is_string($k)) {
                        $email = $k;
                        $name = $v;
                    } else {
                        $email = $v;
                    }

                    $vars = $this->prepareVars($email);
                    $this->injectVars($subjectContent, $htmlContent, $plainContent, $vars);
                    $this->prepareMessageBody($subjectContent, $htmlContent, $plainContent);
                    if (null === $name) {
                        $to = $email;
                    } else {
                        $to = [$email => $name];
                    }
                    $this->message->setTo($to);
                    $totalSent += $mailer->send($this->message);

                } catch (\Swift_SwiftException $e) {
                    $processNextItem = true;
                    // re-throw the exception from onBatchExceptionCaught if you want...
                    $this->onBatchExceptionCaught($e, $processNextItem);
                    if (false === $processNextItem) {
                        break;
                    }
                }

            }
        } else {
            /**
             * merge mode, each recipient receives the same shared email,
             * and the "to" field contains all the recipients addresses.
             */
            $vars = $this->prepareVars();
            $this->injectVars($subjectContent, $htmlContent, $plainContent, $vars);
            $this->prepareMessageBody($subjectContent, $htmlContent, $plainContent);
            $this->message->setTo($this->toRecipients);
            $totalSent += $mailer->send($this->message);
        }


        return $totalSent;
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

    protected function onBatchExceptionCaught(\Swift_SwiftException $e, &$processNextItem = true)
    {
        throw $e; // override this method if you want...
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

    private function prepareBody()
    {
        /**
         * Set the body
         */
        $htmlContent = null;
        $plainContent = null;
        /**
         * Using a template
         */
        if (null !== $this->templateName) {
            if (null !== $this->templateLoader) {
                $this->templateLoader->load($this->templateName);
                $htmlContent = $this->templateLoader->getHtmlContent();
                $plainContent = $this->templateLoader->getPlainContent();
            } else {
                throw new UmailException("Cannot use template " . $this->templateName . " because no TemplateLoader is set.");
            }
        } else {
            /**
             * Using the default htmlBody and plainBody methods
             */
            $htmlContent = $this->htmlText;
            $plainContent = $this->plainText;
        }
        return [$htmlContent, $plainContent];
    }


    private function prepareVars($email = null)
    {
        $vars = $this->commonVars;
        if (null !== $email && is_callable($this->emailVarsCb)) {
            $emailVars = call_user_func($this->emailVarsCb, $email);
            $vars = array_merge($vars, $emailVars);
        }
        return $vars;
    }


    private function injectVars(&$subjectContent, &$htmlContent, &$plainContent, array $vars)
    {
        $keys = array_keys($vars);
        if (is_callable($this->varRefWrapper)) {
            $keys = array_map($this->varRefWrapper, $keys);
        }
        $values = array_values($vars);
        if (is_string($subjectContent)) {
            $subjectContent = str_replace($keys, $values, $subjectContent);
        }
        if (is_string($htmlContent)) {
            $htmlContent = str_replace($keys, $values, $htmlContent);
        }
        if (is_string($plainContent)) {
            $plainContent = str_replace($keys, $values, $plainContent);
        }
    }

    private function prepareMessageBody($subjectText, $htmlText, $plainText)
    {
        if (null !== $subjectText) {
            $this->message->setSubject($subjectText);
        }

        if (null !== $this->file) {
            $attachment = \Swift_Attachment::fromPath($this->file, $this->fileMimeType);
            if (null !== $this->fileName) {
                $attachment->setFilename($this->fileName);
            }
            $this->message->attach($attachment);
        }

        /**
         * Default htmlBody/plainBody gymnastic
         */
        if (null === $htmlText && null !== $plainText) {
            $this->message->setBody($plainText);
        } elseif (null !== $htmlText && null === $plainText) {
            $this->message->setBody($htmlText);
        } elseif (null !== $htmlText && null !== $plainText) {
            $this->message->setBody($htmlText, 'text/html');
            $this->message->addPart($plainText, 'text/plain');
        }
    }
}