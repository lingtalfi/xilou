<?php

namespace Mailer;

/**
 * http://swiftmailer.org/docs/messages.html
 */
abstract class Mailer
{

    public static $debug = false;

    private $_to;
    private $_sender;
    private $_from;
    private $_replyTo;
    private $_subject;
    private $_messagePlain;
    private $_messageHtml;

    public function to($recipients)
    {
        $this->_to = $recipients;
        return $this;
    }

    public function sender($recipient)
    {
        $this->_sender = $recipient;
        return $this;
    }

    public function from($recipients)
    {
        $this->_from = $recipients;
        return $this;
    }

    public function replyTo($recipient)
    {
        $this->_replyTo = $recipient;
        return $this;
    }

    public function subject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    public function messagePlain($msg)
    {
        $this->_messagePlain = $msg;
        return $this;
    }

    public function messageHtml($msg)
    {
        $this->_messageHtml = $msg;
        return $this;
    }

    public function send()
    {
        if (true === self::$debug) {
            return $this->getDebugNbSent();
        }
        $message = \Swift_Message::newInstance();
        if (null !== $this->_subject) {
            $message->setSubject($this->_subject);
        }
        if (null !== $this->_from) {
            $message->setFrom($this->_from);
        }
        if (null !== $this->_sender) {
            $message->setSender($this->_sender);
        }
        if (null !== $this->_replyTo) {
            $message->setReplyTo($this->_replyTo);
        }
        if (null !== $this->_to) {
            $message->setTo($this->_to);
        }
        if (null !== $this->_messagePlain) {
            $message->setBody($this->_messagePlain);
            if (null !== $this->_messageHtml) {
                $message->addPart($this->_messageHtml, 'text/html');
            }
        } elseif (null !== $this->_messageHtml) {
            $message->setBody($this->_messageHtml);
        }


        $transport = $this->getTransport();
        $mailer = \Swift_Mailer::newInstance($transport);


        // Send the message
        return $mailer->send($message);
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    protected function getTransport()
    {
        return \Swift_SmtpTransport::newInstance('smtp.example.org', 25)
            ->setUsername('your username')
            ->setPassword('your password');
    }


    protected function getDebugNbSent(){
        if (is_string($this->_to)) {
            return 1;
        } elseif (is_array($this->_to)) {
            return count($this->_to);
        }
        return 0;
    }

}