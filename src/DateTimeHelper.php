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
     * @return \DateTimeImmutable[]|array
     */
    public function getDatesFromDateRangeAsArrayOfObjects(DateRange $dateRange): array
    {
        $datesAsObjects = [];

        if ($dateRange->getSince()->format(self::INTERNAL_DATE_FORMAT) !== $dateRange->getTill()->format(self::INTERNAL_DATE_FORMAT)) {
            $till = \DateTime::createFromFormat(
                'U',
                (string) $dateRange->getTill()->getTimestamp(),
                $dateRange->getTill()->getTimezone()
            );

            if (!$till instanceof \DateTime) {
                throw new UnexpectedValueException(\sprintf('Could not create %s object', \DateTime::class));
            }

            $period = new \DatePeriod($dateRange->getSince(), new \DateInterval('P1D'), $till);

            /** @var \DateTime $date */
            foreach ($period as $date) {
                $datesAsObjects[] = $date;
            }
        } else {
            // If since and till dates are equal, then only one date in range
            $datesAsObjects[] = $dateRange->getSince();
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
}
