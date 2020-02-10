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
     * @param \DateTimeZone|null $timeZone
     *
     * @return \DateTimeImmutable
     */
    public function getCurrentDatetimeImmutable(?\DateTimeZone $timeZone = null): \DateTimeImmutable;

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
}
