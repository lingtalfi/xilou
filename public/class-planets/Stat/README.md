Stat
============
2016-12-26


Store/fetch stats for your website.


Philosophy
=============

There are two distinct parts:

- capturing the data 
- analyzing the data


Capturing is done on the front end and should be as fast as possible.
Therefore, we use file system with minimal configuration overhead rather than a database and complex objects.


Analyzing should be powerful, time and performance is not a crucial issue.


By convention, all capture tools have a capture method, which arguments vary on a per-capturer basis.


Per day analysis philosophy
--------------------

For "per day" type of stats, I came up with the following system.

The analyzer tool should be able to extract out the stats for one day in a form of an array.
For instance, in the case of a simple counter, it could return the following array:

```php
$stats = [
    "count" => 460,
];
```

If there is more data, entries are added (array scales well with unknown complexity),
for instance:

```php
$stats = [
    "lang_en" => 460,
    "lang_fr" => 480,
    "browser_1" => 39,
    "browser_2" => 98,
];
```

When data are flatten like that, then we can create an Analyzer that can work on a range of time:
Here is a working example code.

```php
<?php


use Stat\Analyzer\PerDayAnalyzer;
use Stat\Extractor\CounterExtractor;


require_once __DIR__ . "/../init.php";


$analyzer = new PerDayAnalyzer();
$extractor = new CounterExtractor();
$dir = __DIR__ . '/stats';
$start = '2015-12-26';
$end = '2016-12-26';
$data = $analyzer->analyze($start, $end, $dir, $extractor);

a($data);
```
 
The above example code would give you the total (sum of the individual counts) for 
the given period.

Sometimes however, it's more useful to have one number per day (for instance if you want to display
one plot per day indicating the number of each day for a given month).

In this case, you can use another cumulating technique, like this:


```php
<?php

use Stat\Analyzer\Cache\PerDayAnalyzerCache;
use Stat\Analyzer\CumulatorPerDayAnalyzer;
use Stat\Extractor\CounterExtractor;

require_once __DIR__ . "/../init.php";


$analyzer = new CumulatorPerDayAnalyzer();
$extractor = new CounterExtractor();
$dir = __DIR__ . '/stats-counter';
$start = '2015-12-26';
$end = '2016-12-26';
$data = $analyzer
    ->setCache(new PerDayAnalyzerCache('stats-counter-range-cache'))// be sure to create the stats-cache directory first
    ->analyze($start, $end, $dir, $extractor);

a($data);
```
 
In the above example we've just used the CumulatorPerDayAnalyzer.
  
It gathers the count for all days rather than summing them.
Therefore, the data that is returned looks like this:
 
```txt
[
    2015-12-26 => 60,
    2015-12-27 => 160,
    2015-12-28 => 45,
    ...
    2016-12-26 => 545,
]
``` 
  
 
 
 
 
The Extractor has an extract method that returns the "flatten" array for a given day.
 
Note that this technique might work with other units of time (not only per day),
as long as the analyzer knows how to combine those units into an human range expressed in days.


What's nice with this technique is that objects are well decoupled; therefore changing
from a simple counter to a full featured stat system is a matter of
using the right extractor:

```php
<?php

use Stat\Analyzer\PerDayAnalyzer;
use Stat\Extractor\WebExtractor;

require_once __DIR__ . "/../init.php";


$analyzer = new PerDayAnalyzer();
$extractor = new WebExtractor();
$dir = __DIR__ . '/stats';
$start = '2015-12-26';
$end = '2016-12-26';
$data = $analyzer->analyze($start, $end, $dir, $extractor);

a($data);
```

In the above example, the WebExtractor returns fancy information:

- language
- browser
- OS
- device type


Caching
=============
2016-12-27

Parsing those information out of a file can take a long time, depending if the range of the request spans
a long period of time.

Therefore, the analyzer uses caching to speed up the answer time.

This might be useful if you need to display data live (like a live report in an admin gui for isntance).

PerDay caching
-----------------
PerDay caching uses a quite simple strategy: it creates the following structure:

- stats-cache
    - days
    - periods
    
    
The philosophy is that it will cache any past day, but not the present one (as the present day's data
might evolve).

Then in the "days" directory, each file represents one day, and it contains the resulting array.
In other words, it saves you the computation of the array.

In the "periods" directory, it stores "past" search periods's results in one file.
This potentially can save a lot of time and memory.


Cache is not enabled by default, here is how you enable it:

```php
<?php

use Stat\Analyzer\Cache\PerDayAnalyzerCache;
use Stat\Analyzer\PerDayAnalyzer;
use Stat\Extractor\WebExtractor;

require_once __DIR__ . "/../init.php";


$analyzer = new PerDayAnalyzer();
$extractor = new WebExtractor();
$dir = __DIR__ . '/stats';
$start = '2015-12-26';
$end = '2016-12-26';
$data = $analyzer
    ->setCache(new PerDayAnalyzerCache('stats-cache')) // be sure to create the stats-cache directory first
    ->analyze($start, $end, $dir, $extractor);

a($data);

```





Fake generator
===================
2016-12-27

If you want to generate some fake data, you can use the fake generator, like this:

```php
$gen = new FakeWebGeneratorUtil();
$gen->generateByPeriod(__DIR__ . '/stats', '2015-12-01', '2016-12-20');
```

Or if you wish to generate fake counter stats:


```php
$o = new FakeCounterGeneratorUtil();
$o->generateByPeriod(__DIR__ . "/stats-counter", "2015-12-01", "2016-12-25");
```



Credits
------------------

In this implementation, for the WebExtractor, I used the https://github.com/cbschuld/Browser.php library.



History Log
------------------
    
- 1.2.1 -- 2017-01-20

    - fix erroneous return in PerDayAnalyzerUtil::getAvailableRange
    
- 1.2.0 -- 2016-12-27

    - add some generator tools
    
- 1.1.0 -- 2016-12-27

    - modify the day path for PerDayWebAnalyzer
    
- 1.0.0 -- 2016-12-27

    - initial commit


    
    
    
    


 
 
 




