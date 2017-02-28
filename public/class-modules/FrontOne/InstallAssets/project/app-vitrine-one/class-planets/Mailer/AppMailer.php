<?php

namespace Mailer;

/**
 *
 * In your app, have something like this:
 *
 * //------------------------------------------------------------------------------/
 * // MAILER CONFIG
 * //------------------------------------------------------------------------------/
 * define('MAILER_HOST', 'smtp.example.org');
 * define('MAILER_PORT', 25);
 * define('MAILER_USER', "your username");
 * define('MAILER_PASS', "your password");
 * define('MAILER_SENDER', "robot@mywebsite.com");
 * define('MAILER_ADMIN', "contact@mywebsite.com");
 *
 */
class AppMailer extends Mailer
{

    public static function create()
    {
        $o = new self();
        $o->sender(MAILER_SENDER);
        return $o;
    }

    public static function notif($msg, $subject = null)
    {
        if (null === $subject) {
            $subject = "New message from " . MAILER_SENDER;
        }
        return self::create()
            ->to(MAILER_ADMIN)
            ->messagePlain($msg)
            ->subject($subject);
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    protected function getTransport()
    {
        return \Swift_SmtpTransport::newInstance(MAILER_HOST, MAILER_PORT)
            ->setUsername(MAILER_USER)
            ->setPassword(MAILER_PASS);
    }

}