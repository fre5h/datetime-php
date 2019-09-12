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

        $this->since = \DateTimeImmutable::createFromFormat(\DateTime::RFC3339, $since->format(\DateTime::RFC3339), $since->getTimezone());
        $this->till = \DateTimeImmutable::createFromFormat(\DateTime::RFC3339, $till->format(\DateTime::RFC3339), $till->getTimezone());
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
        return $this->since->format(self::INTERNAL_DATE_FORMAT) === $dateRange->getSince()->format(self::INTERNAL_DATE_FORMAT)
            && $this->till->format(self::INTERNAL_DATE_FORMAT) === $dateRange->getTill()->format(self::INTERNAL_DATE_FORMAT)
            && $this->since->getTimezone()->getName() === $dateRange->getTill()->getTimezone()->getName()
            && $this->till->getTimezone()->getName() === $dateRange->getTill()->getTimezone()->getName()
        ;
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
