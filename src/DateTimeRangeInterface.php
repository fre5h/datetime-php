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
 * DateTimeRangeInterface.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
interface DateTimeRangeInterface
{
    /**
     * @return \DateTimeZone
     */
    public function getTimezone(): \DateTimeZone;

    /**
     * @return string
     */
    public function getTimezoneName(): string;

    /**
     * @return \DateTimeImmutable
     */
    public function getSince(): \DateTimeImmutable;

    /**
     * @return \DateTimeImmutable
     */
    public function getTill(): \DateTimeImmutable;

    /**
     * @param DateTimeRangeInterface $dateTimeRange
     *
     * @return bool
     */
    public function isEqual(self $dateTimeRange): bool;

    /**
     * @param DateTimeRangeInterface $dateTimeRange
     *
     * @return bool
     */
    public function intersects(self $dateTimeRange): bool;
}
