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
 * DateRangeInterface.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
interface DateRangeInterface
{
    /**
     * @return \DateTimeImmutable
     */
    public function getSince(): \DateTimeImmutable;

    /**
     * @return \DateTimeImmutable
     */
    public function getTill(): \DateTimeImmutable;

    /**
     * @param DateRangeInterface $dateRange
     *
     * @return bool
     */
    public function isEqual(self $dateRange): bool;

    /**
     * @param DateRangeInterface $dateRange
     *
     * @return bool
     */
    public function intersects(self $dateRange): bool;
}
