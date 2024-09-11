<?php
/*
 * This file is part of the DateTime library.
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fresh\DateTime;

/**
 * TimeConstants.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
final class TimeConstants
{
    final public const int NUMBER_OF_MILLISECONDS_IN_A_SECOND = 1000;
    final public const int NUMBER_OF_SECONDS_IN_A_MINUTE = 60;
    final public const int NUMBER_OF_MINUTES_IN_AN_HOUR = 60;
    final public const int NUMBER_OF_SECONDS_IN_AN_HOUR = self::NUMBER_OF_SECONDS_IN_A_MINUTE * self::NUMBER_OF_MINUTES_IN_AN_HOUR;
    final public const int NUMBER_OF_HOURS_IN_A_DAY = 24;
    final public const int NUMBER_OF_SECONDS_IN_A_DAY = self::NUMBER_OF_HOURS_IN_A_DAY * self::NUMBER_OF_SECONDS_IN_AN_HOUR;

    final public const int NUMBER_OF_DAYS_IN_A_WEEK = 7;
    final public const int NUMBER_OF_MONTHS_IN_A_YEAR = 12;
    final public const int NUMBER_OF_DAYS_IN_A_YEAR = 365;
    final public const int NUMBER_OF_DAYS_IN_A_LEAP_YEAR = 366;

    final public const string DAY_MONDAY = 'monday';
    final public const string DAY_MONDAY_SHORT = 'mon';
    final public const string DAY_TUESDAY = 'tuesday';
    final public const string DAY_TUESDAY_SHORT = 'tue';
    final public const string DAY_WEDNESDAY = 'wednesday';
    final public const string DAY_WEDNESDAY_SHORT = 'wed';
    final public const string DAY_THURSDAY = 'thursday';
    final public const string DAY_THURSDAY_SHORT = 'thu';
    final public const string DAY_FRIDAY = 'friday';
    final public const string DAY_FRIDAY_SHORT = 'fri';
    final public const string DAY_SATURDAY = 'saturday';
    final public const string DAY_SATURDAY_SHORT = 'sat';
    final public const string DAY_SUNDAY = 'sunday';
    final public const string DAY_SUNDAY_SHORT = 'sun';

    final public const string MONTH_JANUARY = 'january';
    final public const string MONTH_JANUARY_SHORT = 'jan';
    final public const string MONTH_FEBRUARY = 'february';
    final public const string MONTH_FEBRUARY_SHORT = 'feb';
    final public const string MONTH_MARCH = 'march';
    final public const string MONTH_MARCH_SHORT = 'mar';
    final public const string MONTH_APRIL = 'april';
    final public const string MONTH_APRIL_SHORT = 'apr';
    final public const string MONTH_MAY = 'may';
    final public const string MONTH_MAY_SHORT = 'may';
    final public const string MONTH_JUNE = 'june';
    final public const string MONTH_JUNE_SHORT = 'jun';
    final public const string MONTH_JULY = 'july';
    final public const string MONTH_JULY_SHORT = 'jul';
    final public const string MONTH_AUGUST = 'august';
    final public const string MONTH_AUGUST_SHORT = 'aug';
    final public const string MONTH_SEPTEMBER = 'september';
    final public const string MONTH_SEPTEMBER_SHORT = 'sep';
    final public const string MONTH_OCTOBER = 'october';
    final public const string MONTH_OCTOBER_SHORT = 'oct';
    final public const string MONTH_NOVEMBER = 'november';
    final public const string MONTH_NOVEMBER_SHORT = 'nov';
    final public const string MONTH_DECEMBER = 'december';
    final public const string MONTH_DECEMBER_SHORT = 'dec';

    final public const array WINTER_MONTHS = [self::MONTH_DECEMBER, self::MONTH_JANUARY, self::MONTH_FEBRUARY];
    final public const array WINTER_MONTHS_SHORT = [self::MONTH_DECEMBER_SHORT, self::MONTH_JANUARY_SHORT, self::MONTH_FEBRUARY_SHORT];
    final public const array SPRING_MONTHS = [self::MONTH_MARCH, self::MONTH_APRIL, self::MONTH_MAY];
    final public const array SPRING_MONTHS_SHORT = [self::MONTH_MARCH_SHORT, self::MONTH_APRIL_SHORT, self::MONTH_MAY_SHORT];
    final public const array SUMMER_MONTHS = [self::MONTH_JUNE, self::MONTH_JULY, self::MONTH_AUGUST];
    final public const array SUMMER_MONTHS_SHORT = [self::MONTH_JUNE_SHORT, self::MONTH_JULY_SHORT, self::MONTH_AUGUST_SHORT];
    final public const array AUTUMN_MONTHS = [self::MONTH_SEPTEMBER, self::MONTH_OCTOBER, self::MONTH_NOVEMBER];
    final public const array AUTUMN_MONTHS_SHORT = [self::MONTH_SEPTEMBER_SHORT, self::MONTH_OCTOBER_SHORT, self::MONTH_NOVEMBER_SHORT];
}
