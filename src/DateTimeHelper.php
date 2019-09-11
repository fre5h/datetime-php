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
 * DateTimeHelper.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeHelper
{
    private const INTERNAL_DATE_FORMAT = 'Y-m-d';

    /**
     * @param \DateTimeZone|null $timeZone
     *
     * @return \DateTime
     */
    public function getCurrentDatetime(?\DateTimeZone $timeZone = null): \DateTime
    {
        return new \DateTime('now', $timeZone);
    }

    /**
     * @param \DateTimeZone|null $timeZone
     *
     * @return \DateTimeImmutable
     */
    public function getCurrentDatetimeImmutable(?\DateTimeZone $timeZone = null): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', $timeZone);
    }

    /**
     * @param DateRange $dateRange
     *
     * @throws UnexpectedValueException
     *
     * @return \DateTimeInterface[]
     */
    public function getDatesFromDateRangeAsArrayOfObjects(DateRange $dateRange): array
    {
        $dateRange->assertSameTimezones();

        $since = $this->cloneDateTime($dateRange->getSince());
        $since->setTime(0, 0);

        $till = $this->cloneDateTime($dateRange->getTill());
        $till->setTime(23, 59, 59);

        $datesAsObjects = [];
        $period = new \DatePeriod($since, new \DateInterval('P1D'), $till);
        foreach ($period as $date) {
            $datesAsObjects[] = $date;
        }

        return $datesAsObjects;
    }

    /**
     * @param DateRange $dateRange
     *
     * @return string[]
     */
    public function getDatesFromDateRangeAsArrayOfStrings(DateRange $dateRange): array
    {
        $datesAsObjects = $this->getDatesFromDateRangeAsArrayOfObjects($dateRange);

        $datesAsStrings = [];
        foreach ($datesAsObjects as $datesAsObject) {
            $datesAsStrings[] = $datesAsObject->format(self::INTERNAL_DATE_FORMAT);
        }

        return $datesAsStrings;
    }

    /**
     * @param \DateTimeInterface $originalDate
     *
     * @throws UnexpectedValueException
     *
     * @return \DateTime
     */
    private function cloneDateTime(\DateTimeInterface $originalDate): \DateTime
    {
        $date = \DateTime::createFromFormat(\DateTime::RFC3339, $originalDate->format(\DateTime::RFC3339), $originalDate->getTimezone());

        if (!$date instanceof \DateTime) {
            throw new UnexpectedValueException(\sprintf('Could not create %s object', \DateTime::class));
        }

        return $date;
    }
}
