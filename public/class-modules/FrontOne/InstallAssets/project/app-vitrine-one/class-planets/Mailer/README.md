Mailer
==============
2016-12-23


A simple mailer wrapper for SwiftMailer.


This is part of the [universe framework](https://github.com/karayabin/universe-snapshot).



How to install
===================
You first need [SwiftMailer](http://swiftmailer.org/).
To install swift mailer, use [composer](https://getcomposer.org/):

```bash
composer require swiftmailer/swiftmailer
```


How to use
===============

The AppMailer class was designed to be fast to use.

You first need to put some constants in your init.

Note: if you don't like constants, you can still use the Mailer class.

```php
//------------------------------------------------------------------------------/
// MAILER CONFIG
//------------------------------------------------------------------------------/
define('MAILER_HOST', 'smtp.example.org');
define('MAILER_PORT', 25);
define('MAILER_USER', "your username");
define('MAILER_PASS', "your password");
define('MAILER_SENDER', "robot@mywebsite.com");
define('MAILER_ADMIN', "contact@mywebsite.com");
```

Now you can use the AppMailer.


Send a message to yourself
--------------------------
To send yourself a message, use the notif method:

```php
AppMailer::create()->notif("alert: an exception occurred in...")->send();
```


Send a message to somebody else
---------------------------------

To send a message to somebody else, use the send method.

```php
$nbMsgSend = AppMailer::create()
    ->subject("Your registration on MyWebsite.com")
    ->messageHtml('Hi <b>Marie</b>. Thank you for registering...')
    ->to("marie@bellemere.com")
    ->send();
```

Debug mode
------------

If you are in development mode, you can use the AppMailer debug mode,
it will skip the sending of mail, but will otherwise work as usual (i.e. returning
the expected output).


```php
AppMailer::$debug = true;
$nbMsgSend = AppMailer::create()
    ->subject("Your registration on MyWebsite.com")
    ->messageHtml('Hi <b>Marie</b>. Thank you for registering...')
    ->to("marie@bellemere.com")
    ->send(); // return 1
```




Dependency
--------------
- [SwiftMailer](http://swiftmailer.org/)



History Log
------------------
    
- 2.0.0 -- 2016-12-23

    - AppMailer->notif now returns the instance and doesn't send the message
    
- 1.0.0 -- 2016-12-23

    - initial commit