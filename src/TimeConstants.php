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
    public const NUMBER_OF_SECONDS_IN_A_MINUTE = 60;
    public const NUMBER_OF_MINUTES_IN_AN_HOUR = 60;
    public const NUMBER_OF_SECONDS_IN_AN_HOUR = self::NUMBER_OF_SECONDS_IN_A_MINUTE * self::NUMBER_OF_MINUTES_IN_AN_HOUR;
    public const NUMBER_OF_HOURS_IN_A_DAY = 24;
    public const NUMBER_OF_SECONDS_IN_A_DAY = self::NUMBER_OF_HOURS_IN_A_DAY * self::NUMBER_OF_SECONDS_IN_AN_HOUR;

    public const NUMBER_OF_DAYS_IN_A_WEEK = 7;
    public const NUMBER_OF_MONTHS_IN_A_YEAR = 12;
    public const NUMBER_OF_DAYS_IN_A_YEAR = 365;
    public const NUMBER_OF_DAYS_IN_A_LEAP_YEAR = 366;
}
