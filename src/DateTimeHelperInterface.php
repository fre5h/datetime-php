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

use Fresh\DateTime\Exception\InvalidArgumentException;
use Fresh\DateTime\Exception\UnexpectedValueException;

/**
 * DateTimeHelperInterface.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
interface DateTimeHelperInterface
{
    /**
     * @param string $timeZoneName
     *
     * @return \DateTimeZone
     */
    public function createDateTimeZone(string $timeZoneName = 'UTC'): \DateTimeZone;

    /**
     * @return \DateTimeZone
     */
    public function createDateTimeZoneUtc(): \DateTimeZone;

    /**
     * @return int
     */
    public function getCurrentTimestamp(): int;

    /**
     * @param \DateTimeZone|null $timeZone
     *
     * @return \DateTime
     */
    public function getCurrentDatetime(?\DateTimeZone $timeZone = null): \DateTime;

    /**
     * @return \DateTime
     */
    public function getCurrentDatetimeUtc(): \DateTime;

    /**
     * @param \DateTimeZone|null $timeZone
     *
     * @return \DateTimeImmutable
     */
    public function getCurrentDatetimeImmutable(?\DateTimeZone $timeZone = null): \DateTimeImmutable;

    /**
     * @return \DateTimeImmutable
     */
    public function getCurrentDatetimeImmutableUtc(): \DateTimeImmutable;

    /**
     * @param DateRangeInterface $dateRange
     *
     * @throws UnexpectedValueException
     *
     * @return \DateTimeInterface[]
     */
    public function getDatesFromDateRangeAsArrayOfObjects(DateRangeInterface $dateRange): array;

    /**
     * @param DateRangeInterface $dateRange
     *
     * @throws UnexpectedValueException
     *
     * @return string[]
     */
    public function getDatesFromDateRangeAsArrayOfStrings(DateRangeInterface $dateRange): array;

    /**
     * @param string             $dateTimeAsString
     * @param string             $dateFormat
     * @param \DateTimeZone|null $timeZone
     *
     * @throws InvalidArgumentException
     *
     * @return \DateTime
     */
    public function createDateTimeFromFormat(string $dateTimeAsString, string $dateFormat, ?\DateTimeZone $timeZone = null): \DateTime;
}
