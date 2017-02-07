Umail
============
2017-02-06



A helper class to send mails.


It's part of the [universe framework](https://github.com/karayabin/universe-snapshot),
and it uses [SwiftMailer](http://swiftmailer.org/) library.




Features
----------
 
- basic email features (subject, to, from, htmlBody, plainBody, ...)  
- variable references system, using {thisNotation} by default 
- attach files, or embed files 
- template system 




Example 1: send an email
--------------------------

Note: I tested this example on 2017-02-06, and it worked 
on my local machine (macbook pro). However, it failed on my iMac (gate timeout error).

In other words, if your machine allows it, you can send emails without using smtp settings (username, password, ...).


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



Example 2: using batch mode or merge mode
--------------------------

In batch mode, each recipient sees only its own mail in the "to" field (of the mail software).
If batch mode is off, each recipient sees all the recipients to which the email was sent. 
Default is batch on.

```php
$batchMode = true; // change this to true|false and observe the "to" field in the received emails
$res = Umail::create()
    ->to([
        'lingtalfi@gmail.com' => 'ling',
        'agenceweb37@gmail.com',
    ], $batchMode)
    ->from('johndoe@gmail.com')
    ->subject("Hi, just a test mail")
    ->htmlBody('Hi, this is <b>just</b> an <span style="color: red">test message</span>')
    ->plainBody('Hi, this is just an test message')
    ->send();
a($res);

```


Example 3: using variables
-----------------------------

Variables can be injected in the body and or the subject of an email.

There are two types of variables:
- common variables: they are the same for every recipient   
- email variables: they depend on the recipient's email address
     
More info in the comments of the UmailInterface source code.
  
     
Below is an example illustrating the use of "common variables".     
     
```php
$res = Umail::create()
    ->to([
        'lingtalfi@gmail.com' => 'ling',
    ])
    ->from('johndoe@gmail.com')
    ->subject("Hi {somebody}, just a test mail")
    ->setVars([
        'message' => 'variable message',
        'somebody' => "there",
    ])
    ->htmlBody('Hi, this is <b>just</b> a <span style="color: red">{message}</span>')
    ->plainBody('Hi, this is just a test message')
    ->send();
a($res);
```


And below is an example showing both common and email variables:

```php
$res = Umail::create()
    ->to([
        'lingtalfi@gmail.com' => 'ling',
    ])
    ->from('johndoe@gmail.com')
    ->subject("Hi {somebody}, just a test mail")
    ->setVars([
        'message' => 'variable message',
    ], function ($email) {
        return [
            'somebody' => substr($email, 0, strpos($email, '@')),
        ];
    })
    ->htmlBody('Hi, this is <b>just</b> a <span style="color: red">{message}</span>')
    ->plainBody('Hi, this is just a test message')
    ->send();
a($res);
```



Example 4: attaching a file
-----------------------------

```php
$file = "/Users/my/Desktop/zilu-db.png";
$res = Umail::create()
    ->to([
        'lingtalfi@gmail.com' => 'ling',
    ])
    ->from('johndoe@gmail.com')
    ->subject("Hi, testing attach file")
    ->htmlBody('Hi, this is <b>just</b> a <span style="color: red">message to test file attachment</span>')
    ->plainBody('Hi, this is just a test message to test file attachment')
    ->attachFile($file)
    ->send();
a($res);
```



Example 5: embedding a file
-----------------------------

Sometimes, attached files are blocked by mail clients.
By embedding your media directly into the message, you have two benefits:

- you circumvent the blocking problem that attached files have 
- you can place the media where you want


```php
$file = "/Users/my/Desktop/ps_product_attribute.png";

$mail = Umail::create();
$cid = $mail->embedFile($file);
$res = $mail->to([
    'lingtalfi@gmail.com' => 'ling',
])
    ->from('johndoe@gmail.com')
    ->subject("Hi, testing attach file")
    ->htmlBody(
        '<html>' .
        ' <head></head>' .
        ' <body>' .
        '  Here is an image <img src="' . // Embed the file
        $cid .
        '" alt="Image" />' .
        '  Rest of message' .
        ' </body>' .
        '</html>'
    )
    ->plainBody('Hi, this is just a test message to test file attachment')
    ->send();
a($res);
```