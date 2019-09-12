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
 * DateTimeHelper.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeHelper implements DateTimeHelperInterface
{
    private const INTERNAL_DATE_FORMAT = 'Y-m-d';

    private $datesCache = [];

    /**
     * {@inheritdoc}
     */
    public function getCurrentDatetime(?\DateTimeZone $timeZone = null): \DateTime
    {
        return new \DateTime('now', $timeZone);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentDatetimeImmutable(?\DateTimeZone $timeZone = null): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', $timeZone);
    }

    /**
     * {@inheritdoc}
     */
    public function getDatesFromDateRangeAsArrayOfObjects(DateRangeInterface $dateRange): array
    {
        $cacheKeyForDateRange = $this->getCacheKeyForDateRange($dateRange);

        if (!isset($this->datesCache[$cacheKeyForDateRange])) {
            $since = DateTimeCloner::cloneIntoDateTime($dateRange->getSince());
            $since->setTime(0, 0);

            $till = DateTimeCloner::cloneIntoDateTime($dateRange->getTill());
            $till->setTime(23, 59, 59);

            $datesAsObjects = [];
            $period = new \DatePeriod($since, new \DateInterval('P1D'), $till);
            foreach ($period as $date) {
                $datesAsObjects[] = $date;
            }

            $this->datesCache[$cacheKeyForDateRange] = $datesAsObjects;
        }

        return $this->datesCache[$cacheKeyForDateRange];
    }

    /**
     * {@inheritdoc}
     */
    public function getDatesFromDateRangeAsArrayOfStrings(DateRangeInterface $dateRange): array
    {
        $datesAsObjects = $this->getDatesFromDateRangeAsArrayOfObjects($dateRange);

        $datesAsStrings = [];
        foreach ($datesAsObjects as $datesAsObject) {
            $datesAsStrings[] = $datesAsObject->format(self::INTERNAL_DATE_FORMAT);
        }

        return $datesAsStrings;
    }

    /**
     * @param DateRangeInterface $dateRange
     *
     * @return string
     */
    private function getCacheKeyForDateRange(DateRangeInterface $dateRange): string
    {
        $since = $dateRange->getSince();
        $till = $dateRange->getTill();

        return \sprintf(
            '%s_%s_%s_%s',
            $since->format(self::INTERNAL_DATE_FORMAT),
            $since->getTimezone()->getName(),
            $till->format(self::INTERNAL_DATE_FORMAT),
            $till->getTimezone()->getName()
        );
    }
}
