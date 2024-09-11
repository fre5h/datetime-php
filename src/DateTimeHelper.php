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
use Psr\Clock\ClockInterface;

/**
 * DateTimeHelper.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeHelper implements ClockInterface, DateTimeHelperInterface
{
    private const string INTERNAL_DATE_FORMAT = 'Y-m-d';
    private const string INTERNAL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /** @var array<string, \DateTimeInterface[]> */
    private array $datesCache = [];

    private ?\DateTimeZone $timeZoneUtc = null;

    /**
     * {@inheritdoc}
     */
    public function now(): \DateTimeImmutable
    {
        return $this->getCurrentDatetimeImmutableUtc();
    }

    /**
     * {@inheritdoc}
     */
    public function createDateTimeZone(string $timeZoneName = 'UTC'): \DateTimeZone
    {
        return new \DateTimeZone($timeZoneName);
    }

    /**
     * {@inheritdoc}
     */
    public function createDateTimeZoneUtc(): \DateTimeZone
    {
        if (!$this->timeZoneUtc instanceof \DateTimeZone) {
            $this->timeZoneUtc = new \DateTimeZone('UTC');
        }

        return $this->timeZoneUtc;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentTimestamp(): int
    {
        return \time();
    }

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
    public function getCurrentDatetimeUtc(): \DateTime
    {
        return new \DateTime('now', $this->createDateTimeZoneUtc());
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
    public function getCurrentDatetimeImmutableUtc(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', $this->createDateTimeZoneUtc());
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
                /** @var \DateTimeInterface $date */
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
     * {@inheritdoc}
     */
    public function createDateTimeFromFormat(string $dateTimeAsString, string $dateFormat = self::INTERNAL_DATETIME_FORMAT, ?\DateTimeZone $timeZone = null): \DateTime
    {
        if ($timeZone instanceof \DateTimeZone) {
            $result = \DateTime::createFromFormat($dateFormat, $dateTimeAsString, $timeZone);
        } else {
            $result = \DateTime::createFromFormat($dateFormat, $dateTimeAsString, $this->createDateTimeZoneUtc());
        }

        if (!$result instanceof \DateTime) {
            throw new InvalidArgumentException(\sprintf('Could not create a \DateTime object from string "%s" from format "%s".', $dateTimeAsString, $dateFormat));
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function createDateTimeImmutableFromFormat(string $dateTimeAsString, string $dateFormat = self::INTERNAL_DATETIME_FORMAT, ?\DateTimeZone $timeZone = null): \DateTimeImmutable
    {
        if ($timeZone instanceof \DateTimeZone) {
            $result = \DateTimeImmutable::createFromFormat($dateFormat, $dateTimeAsString, $timeZone);
        } else {
            $result = \DateTimeImmutable::createFromFormat($dateFormat, $dateTimeAsString, $this->createDateTimeZoneUtc());
        }

        if (!$result instanceof \DateTimeImmutable) {
            throw new InvalidArgumentException(\sprintf('Could not create a \DateTimeImmutable object from string "%s" from format "%s".', $dateTimeAsString, $dateFormat));
        }

        return $result;
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
            $till->getTimezone()->getName(),
        );
    }
}
