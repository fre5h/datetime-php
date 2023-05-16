# DateTime

PHP library that provides additional functions for processing dates & times. 🐘 🕒 📅

[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/fre5h/datetime-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/fre5h/datetime-php/)
[![Build Status](https://img.shields.io/github/actions/workflow/status/fre5h/datetime-php/ci.yaml?branch=main&style=flat-square)](https://github.com/fre5h/datetime-php/actions?query=workflow%3ACI+branch%3Amain+)
[![CodeCov](https://img.shields.io/codecov/c/github/fre5h/datetime-php.svg?style=flat-square)](https://codecov.io/github/fre5h/datetime-php)
[![License](https://img.shields.io/packagist/l/fresh/datetime.svg?style=flat-square)](https://packagist.org/packages/fresh/datetime)
[![Latest Stable Version](https://img.shields.io/packagist/v/fresh/datetime.svg?style=flat-square)](https://packagist.org/packages/fresh/datetime)
[![Total Downloads](https://img.shields.io/packagist/dt/fresh/datetime.svg?style=flat-square)](https://packagist.org/packages/fresh/datetime)
[![StyleCI](https://styleci.io/repos/190854938/shield?style=flat-square)](https://styleci.io/repos/190854938)
[![Gitter](https://img.shields.io/badge/gitter-join%20chat-brightgreen.svg?style=flat-square)](https://gitter.im/fre5h/datetime-php)

## Requirements

* PHP 8.2

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
$now2 = $dateTimeHelper->getCurrentDatetime(new \DateTimeZone('Europe/Kiev')); // Or with custom timezone
$now3 = $dateTimeHelper->getCurrentDatetimeUtc(); // Always in UTC
$now4 = $dateTimeHelper->getCurrentDatetimeImmutable();
$now5 = $dateTimeHelper->getCurrentDatetimeImmutable(new \DateTimeZone('Europe/Kiev')); // Or with custom timezone
$now6 = $dateTimeHelper->getCurrentDatetimeImmutableUtc(); // Always in UTC
```

Compatible with [PSR-20: Clock](https://www.php-fig.org/psr/psr-20/).

```php
use Fresh\DateTime\DateTimeHelper;

$dateTimeHelper = new DateTimeHelper();

$now = $dateTimeHelper->now(); // \DateTimeImmutable in UTC
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

### Immutable `DateRange` ValueObject

You often needed to manipulate with since/till dates, so-called date ranges.
By its nature, date range is a `ValueObject`, it can be reused many times for different purposes.
This library provides a `DateRange` immutable class, which is not able to be changed after its creation.
`DateRange` operates only with dates and ignore time.

```php
use Fresh\DateTime\DateRange;

$dateRange1 = new DateRange(new \DateTime('yesterday'), new \DateTime('tomorrow'));
$dateRange2 = new DateRange(new \DateTime('yesterday'), new \DateTime('tomorrow', new \DateTimeZone('Europe/Kiev')));

// There is also the `isEqual` method to compare two DateRange objects.
$dateRange1->isEqual($dateRange2); // Returns FALSE, because date ranges have different timezones
```

### Immutable `DateTimeRange` ValueObject

This library provides  also immutable class `DateTimeRange`, instead of `DateRange` it checks date and time.

```php
use Fresh\DateTime\DateTimeRange;

$dateTimeRange1 = new DateTimeRange(new \DateTime('2000-01-01 19:00:00'), new \DateTime('2000-01-01 21:00:00'));
$dateTimeRange2 = new DateTimeRange(new \DateTime('2000-01-01 19:00:00'), new \DateTime('2000-01-01 21:00:00', new \DateTimeZone('Europe/Kiev')));
$dateTimeRange3 = new DateTimeRange(new \DateTime('2000-01-01 20:00:00'), new \DateTime('2000-01-01 22:00:00'));

// There is also the `isEqual` method to compare two DateTimeRange objects.
$dateTimeRange1->isEqual($dateTimeRange2); // Returns FALSE, because datetime ranges have different timezones

// There is also the `intersects` method to check if datetime range intersected each other.
$dateTimeRange1->intersects($dateTimeRange3); // Returns TRUE, because datetime ranges are intersected
```

#### Examples of date ranges with intersection

![Example of intersection](docs/images/intersect.png "Example of intersection")

#### Examples of date ranges without intersection

![Example of no intersection](docs/images/does_not_intersect.png "Example of no intersection")

### Getting array of objects/strings of all dates in date range

```php
use Fresh\DateTime\DateTimeHelper;
use Fresh\DateTime\DateRange;

$dateTimeHelper = new DateTimeHelper();

$dateRange = new DateRange(new \DateTime('1970-01-01'), new \DateTime('1970-01-03'));

// Creates array with values ['1970-01-01', '1970-01-02', '1970-01-03']
$datesAsStrings = $dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($dateRange);

// Creates array of \DateTime objects for dates: '1970-01-01', '1970-01-02', '1970-01-03'
$datesAsObjects = $dateTimeHelper->getDatesFromDateRangeAsArrayOfObjects($dateRange);
```

### DateTimeCloner allows to clone dates into `\DateTime` or `\DateTimeImmutable` instances

```php
use Fresh\DateTime\DateTimeCloner;

$dateTimeCloner = new DateTimeCloner();

$date1 = new \DateTime();
$dateImmutable1 = $dateTimeCloner::cloneIntoDateTimeImmutable($date1); // Returns \DateTimeImmutable object
$date2 = $dateTimeCloner::cloneIntoDateTime($dateImmutable1); // Returns \DateTime object
```

## Contributing 🤝

See [CONTRIBUTING](https://github.com/fre5h/datetime-php/blob/master/.github/CONTRIBUTING.md) file.
