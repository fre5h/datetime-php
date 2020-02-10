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
    public const NUMBER_OF_MILLISECONDS_IN_A_SECOND = 1000;
    public const NUMBER_OF_SECONDS_IN_A_MINUTE = 60;
    public const NUMBER_OF_MINUTES_IN_AN_HOUR = 60;
    public const NUMBER_OF_SECONDS_IN_AN_HOUR = self::NUMBER_OF_SECONDS_IN_A_MINUTE * self::NUMBER_OF_MINUTES_IN_AN_HOUR;
    public const NUMBER_OF_HOURS_IN_A_DAY = 24;
    public const NUMBER_OF_SECONDS_IN_A_DAY = self::NUMBER_OF_HOURS_IN_A_DAY * self::NUMBER_OF_SECONDS_IN_AN_HOUR;

    public const NUMBER_OF_DAYS_IN_A_WEEK = 7;
    public const NUMBER_OF_MONTHS_IN_A_YEAR = 12;
    public const NUMBER_OF_DAYS_IN_A_YEAR = 365;
    public const NUMBER_OF_DAYS_IN_A_LEAP_YEAR = 366;

    public const DAY_MONDAY = 'monday';
    public const DAY_MONDAY_SHORT = 'mon';
    public const DAY_TUESDAY = 'tuesday';
    public const DAY_TUESDAY_SHORT = 'tue';
    public const DAY_WEDNESDAY = 'wednesday';
    public const DAY_WEDNESDAY_SHORT = 'wed';
    public const DAY_THURSDAY = 'thursday';
    public const DAY_THURSDAY_SHORT = 'thu';
    public const DAY_FRIDAY = 'friday';
    public const DAY_FRIDAY_SHORT = 'fri';
    public const DAY_SATURDAY = 'saturday';
    public const DAY_SATURDAY_SHORT = 'sat';
    public const DAY_SUNDAY = 'sunday';
    public const DAY_SUNDAY_SHORT = 'sun';

    public const MONTH_JANUARY = 'january';
    public const MONTH_JANUARY_SHORT = 'jan';
    public const MONTH_FEBRUARY = 'february';
    public const MONTH_FEBRUARY_SHORT = 'feb';
    public const MONTH_MARCH = 'march';
    public const MONTH_MARCH_SHORT = 'mar';
    public const MONTH_APRIL = 'april';
    public const MONTH_APRIL_SHORT = 'apr';
    public const MONTH_MAY = 'may';
    public const MONTH_MAY_SHORT = 'may';
    public const MONTH_JUNE = 'june';
    public const MONTH_JUNE_SHORT = 'jun';
    public const MONTH_JULY = 'july';
    public const MONTH_JULY_SHORT = 'jul';
    public const MONTH_AUGUST = 'august';
    public const MONTH_AUGUST_SHORT = 'aug';
    public const MONTH_SEPTEMBER = 'september';
    public const MONTH_SEPTEMBER_SHORT = 'sep';
    public const MONTH_OCTOBER = 'october';
    public const MONTH_OCTOBER_SHORT = 'oct';
    public const MONTH_NOVEMBER = 'november';
    public const MONTH_NOVEMBER_SHORT = 'nov';
    public const MONTH_DECEMBER = 'december';
    public const MONTH_DECEMBER_SHORT = 'dec';

    public const WINTER_MONTHS = [self::MONTH_DECEMBER, self::MONTH_JANUARY, self::MONTH_FEBRUARY];
    public const WINTER_MONTHS_SHORT = [self::MONTH_DECEMBER_SHORT, self::MONTH_JANUARY_SHORT, self::MONTH_FEBRUARY_SHORT];
    public const SPRING_MONTHS = [self::MONTH_MARCH, self::MONTH_APRIL, self::MONTH_MAY];
    public const SPRING_MONTHS_SHORT = [self::MONTH_MARCH_SHORT, self::MONTH_APRIL_SHORT, self::MONTH_MAY_SHORT];
    public const SUMMER_MONTHS = [self::MONTH_JUNE, self::MONTH_JULY, self::MONTH_AUGUST];
    public const SUMMER_MONTHS_SHORT = [self::MONTH_JUNE_SHORT, self::MONTH_JULY_SHORT, self::MONTH_AUGUST_SHORT];
    public const AUTUMN_MONTHS = [self::MONTH_SEPTEMBER, self::MONTH_OCTOBER, self::MONTH_NOVEMBER];
    public const AUTUMN_MONTHS_SHORT = [self::MONTH_SEPTEMBER_SHORT, self::MONTH_OCTOBER_SHORT, self::MONTH_NOVEMBER_SHORT];
}
