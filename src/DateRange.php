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

use Fresh\DateTime\Exception\LogicException;

/**
 * DateRange.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
final class DateRange implements DateRangeInterface
{
    private const INTERNAL_DATE_FORMAT = 'Y-m-d';

    /** @var \DateTimeImmutable */
    private $since;

    /** @var \DateTimeImmutable */
    private $till;

    /**
     * @param \DateTimeInterface $since
     * @param \DateTimeInterface $till
     */
    public function __construct(\DateTimeInterface $since, \DateTimeInterface $till)
    {
        $this->assertSameTimezones($since, $till);

        $this->since = DateTimeCloner::cloneIntoDateTimeImmutable($since);
        $this->till = DateTimeCloner::cloneIntoDateTimeImmutable($till);
    }

    /**
     * {@inheritdoc}
     */
    public function getSince(): \DateTimeImmutable
    {
        return $this->since;
    }

    /**
     * {@inheritdoc}
     */
    public function getTill(): \DateTimeImmutable
    {
        return $this->till;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqual(DateRangeInterface $dateRange): bool
    {
        $dateRangeSince = $dateRange->getSince();
        $dateRangeTill = $dateRange->getTill();

        return $this->since->format(self::INTERNAL_DATE_FORMAT) === $dateRangeSince->format(self::INTERNAL_DATE_FORMAT)
               && $this->till->format(self::INTERNAL_DATE_FORMAT) === $dateRangeTill->format(self::INTERNAL_DATE_FORMAT)
               && $this->since->getTimezone()->getName() === $dateRangeSince->getTimezone()->getName()
               && $this->till->getTimezone()->getName() === $dateRangeTill->getTimezone()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function intersects(DateRangeInterface $dateRange): bool
    {
        $givenDateRangeSince = $dateRange->getSince();
        $givenDateRangeTill = $dateRange->getTill();

        switch (true) {
            case $this->since === $givenDateRangeSince && $this->till === $givenDateRangeTill: // Current date range is equal to the given date range
            case $givenDateRangeSince < $this->since && $this->till < $givenDateRangeTill: // Current date range is fully inside the given date range
            case $this->since < $givenDateRangeSince && $givenDateRangeTill < $this->till: // Given date range is fully inside the current date range
            case $givenDateRangeSince <= $this->since && $this->since < $givenDateRangeTill: // Current date range beginning is inside the given date range
            case $givenDateRangeSince < $this->till && $this->till <= $givenDateRangeTill: // Current date range ending is inside the given date range
                return true;
            default:
                return false;
        }
    }

    /**
     * @param \DateTimeInterface $since
     * @param \DateTimeInterface $till
     *
     * @throws LogicException
     */
    private function assertSameTimezones(\DateTimeInterface $since, \DateTimeInterface $till): void
    {
        if ($since->getTimezone()->getName() !== $till->getTimezone()->getName()) {
            throw new LogicException('Dates have different timezones');
        }
    }
}
