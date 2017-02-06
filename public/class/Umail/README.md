Umail
============
2017-02-06



A helper class to send mails.


It's part of the [universe framework](https://github.com/karayabin/universe-snapshot),
and it uses [SwiftMailer](http://swiftmailer.org/) library.




Example 1: send an email
--------------------------

Note: I tested this example on 2017-02-06, and it worked 
on my local machine (macbook pro).

In other words, you can send emails without setting passwords.


```php
<?php


use Umail\Umail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// SEND SIMPLE MAIL
//------------------------------------------------------------------------------/
$res = Umail::create()
    ->to('myemail@gmail.com')
    ->from('johndoe@gmail.com')
    ->subject("Hi, just a test mail")
    ->htmlBody('Hi, this is <b>just</b> an <span style="color: red">test message</span>')
    ->plainBody('Hi, this is just an test message')
    ->send();
a($res);
```



Example 2: using a template
--------------------------

You can use an html template if you want, it will override the 
htmlBody and plainBody methods, but is a faster way to send
complex html mails.




