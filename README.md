doctrine-dbal-datetimeutc
=========================

[![paypal](https://img.shields.io/badge/Donate-Paypal-blue.svg)](http://paypal.me/nloges)

[![PHP Version](https://img.shields.io/packagist/php-v/shapecode/doctrine-dbal-datetimeutc.svg)](https://packagist.org/packages/shapecode/doctrine-dbal-datetimeutc)
[![Latest Stable Version](https://img.shields.io/packagist/v/shapecode/doctrine-dbal-datetimeutc.svg?label=stable)](https://packagist.org/packages/shapecode/doctrine-dbal-datetimeutc)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/shapecode/doctrine-dbal-datetimeutc.svg?label=unstable)](https://packagist.org/packages/shapecode/doctrine-dbal-datetimeutc)
[![Total Downloads](https://img.shields.io/packagist/dt/shapecode/doctrine-dbal-datetimeutc.svg)](https://packagist.org/packages/shapecode/doctrine-dbal-datetimeutc)
[![Monthly Downloads](https://img.shields.io/packagist/dm/shapecode/doctrine-dbal-datetimeutc.svg)](https://packagist.org/packages/shapecode/doctrine-dbal-datetimeutc)
[![Daily Downloads](https://img.shields.io/packagist/dd/shapecode/doctrine-dbal-datetimeutc.svg)](https://packagist.org/packages/shapecode/doctrine-dbal-datetimeutc)
[![License](https://img.shields.io/packagist/l/shapecode/doctrine-dbal-datetimeutc.svg)](https://packagist.org/packages/shapecode/cron-bundle)

A Doctine DBAL [Custom Mapping Type](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/custom-mapping-types.html) allowing the use of PHP DateTime objects automatically set to the UTC timezone.

Databases [suck at timezones](http://derickrethans.nl/storing-date-time-in-database.html).  The best way to deal with this is to store the date and time in UTC and seperately store the timezone that should be used for display purposes.  By default, PHP will create DateTime objects set the server's timezone.  This custom type overrides this to set the timezone to UTC, allowing you to later convert to the proper timezone for display.

Install via composer:

```bash
composer req shapecode/doctrine-dbal-datetimeutc
``` 

Add the custom type before instantiating your entity manager:

```php
use Doctrine\DBAL\Types\Type;
use Shapecode\Doctrine\DBAL\Types\DateTimeUTCType;

Type::addType(DateTimeUTCType::DATETIMEUTC, DateTimeUTCType::class);
```

Enjoy!
