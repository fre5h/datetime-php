# DateTime

🕒 📅 🐘 PHP library that provides additional functions for processing dates & times.

[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/fre5h/datetime-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/fre5h/datetime-php/)
[![Build Status](https://img.shields.io/travis/fre5h/datetime-php/master.svg?style=flat-square)](https://travis-ci.org/fre5h/datetime-php)
[![CodeCov](https://img.shields.io/codecov/c/github/fre5h/datetime-php.svg?style=flat-square)](https://codecov.io/github/fre5h/datetime-php)
[![License](https://img.shields.io/packagist/l/fresh/datetime.svg?style=flat-square)](https://packagist.org/packages/fresh/datetime)
[![Latest Stable Version](https://img.shields.io/packagist/v/fresh/datetime.svg?style=flat-square)](https://packagist.org/packages/fresh/datetime)
[![Total Downloads](https://img.shields.io/packagist/dt/fresh/datetime.svg?style=flat-square)](https://packagist.org/packages/fresh/datetime)
[![StyleCI](https://styleci.io/repos/190854938/shield?style=flat-square)](https://styleci.io/repos/190854938)
[![Gitter](https://img.shields.io/badge/gitter-join%20chat-brightgreen.svg?style=flat-square)](https://gitter.im/fre5h/datetime-php)

## Requirements

* PHP 7.3.0 *and later*

## Installation 🌱

```composer req fresh/datetime```

## Features 🎁

### Popular time constants

Number of seconds in a minute, number of minutes in an hour, etc.

```php
use Fresh\DateTime\TimeConstants;

echo TimeConstants::NUMBER_OF_SECONDS_IN_AN_HOUR; // etc.
```

### Methods for creating current `\DateTime` and `\DateTimeImmutable` objects (convenient for testing)

If you use separate class for creating datetime objects, you can mock these methods in your code and have the expected `\DateTime` object what you need.

```php
use Fresh\DateTime\DateTimeHelper;

$dateTimeHelper = new DateTimeHelper();

$now1 = $dateTimeHelper->getCurrentDatetime();
$now2 = $dateTimeHelper->getCurrentDatetime(new DateTimeZone('Europe/Kiev')); // Or with custom timezone
$now3 = $dateTimeHelper->getCurrentDatetimeUtc(); // Always in UTC
$now4 = $dateTimeHelper->getCurrentDatetimeImmutable();
$now5 = $dateTimeHelper->getCurrentDatetimeImmutable(new DateTimeZone('Europe/Kiev')); // Or with custom timezone
$now6 = $dateTimeHelper->getCurrentDatetimeImmutableUtc(); // Always in UTC
```

### Method for getting current timestamp

```php
use Fresh\DateTime\DateTimeHelper;

$dateTimeHelper = new DateTimeHelper();

$timestamp = $dateTimeHelper->getCurrentTimestamp();

```

### Method for creating `\DateTimeZone` object

If you create a `\DateTimeZone` object directly in your code, you will not be able to mock it in tests.
So there is a specific method for creating timezone object.

```php
use Fresh\DateTime\DateTimeHelper;

$dateTimeHelper = new DateTimeHelper();

$dateTimeZone1 = $dateTimeHelper->createDateTimeZone(); // UTC by default
$dateTimeZone2 = $dateTimeHelper->createDateTimeZone('Europe/Kiev'); // Or with custom timezone
$dateTimeZone3 = $dateTimeHelper->createDateTimeZoneUtc(); // Another method to get UTC timezone
```

### Immutable ValueObject `DateRange`

You often need to manipulate with since/till dates, so-called date ranges.
By its nature, date range is a `ValueObject`, it can be reused many times for different purposes.
This library provides a `DateRange` immutable class, which is not able to be changed after its creation. 

```php
use Fresh\DateTime\DateRange;

$dateRange1 = new DateRange(new DateTime('yesterday'), new DateTime('tomorrow'));
$dateRange2 = new DateRange(new DateTime('yesterday'), new DateTime('tomorrow', new DateTimeZone('Europe/Kiev')));

// There is also the `isEqual` method to compare two DateRange objects.
$dateRange1->isEqual($dateRange2); // Returns FALSE, because date ranges have different timezones
```

### Getting array of objects/strings of all dates in date range

```php
use Fresh\DateTime\DateTimeHelper;
use Fresh\DateTime\DateRange;

$dateTimeHelper = new DateTimeHelper();

$dateRange = new DateRange(new DateTime('1970-01-01'), new DateTime('1970-01-03'));

// Creates array with values ['1970-01-01', '1970-01-02', '1970-01-03']
$datesAsStrings = $dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($dateRange);

// Creates array of \DateTime objects for dates: '1970-01-01', '1970-01-02', '1970-01-03'
$datesAsObjects = $dateTimeHelper->getDatesFromDateRangeAsArrayOfObjects($dateRange);
```

### DateTimeCloner allows to clone dates into `\DateTime` or `\DateTimeImmutable` instances

```php
use Fresh\DateTime\DateTimeCloner;

$dateTimeCloner = new DateTimeCloner();

$date1 = new DateTime();
$dateImmutable1 = $dateTimeCloner->cloneIntoDateTimeImmutable($date1); // Returns \DateTimeImmutable object
$date2 = $dateTimeCloner->cloneIntoDateTime($dateImmutable1); // Returns \DateTime object
```

## Contributing 🤝

See [CONTRIBUTING](https://github.com/fre5h/datetime-php/blob/master/.github/CONTRIBUTING.md) file.
