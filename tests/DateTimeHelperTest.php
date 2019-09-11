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

namespace Fresh\DateTime\Tests;

use Fresh\DateTime\DateRange;
use Fresh\DateTime\DateTimeHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * DateTimeHelperTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeHelperTest extends TestCase
{
    /** @var DateRange|MockObject */
    private $dateRange;

    /** @var DateTimeHelper */
    private $dateTimeHelper;

    protected function setUp(): void
    {
        $this->dateRange = $this->createMock(DateRange::class);
        $this->dateTimeHelper = new DateTimeHelper();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dateRange,
            $this->dateTimeHelper,
        );
    }

    public function testGetCurrentDatetime(): void
    {
        $now = $this->dateTimeHelper->getCurrentDatetime();
        self::assertInstanceOf(\DateTime::class, $now);

        $now = $this->dateTimeHelper->getCurrentDatetime(new \DateTimeZone('Europe/Kiev'));
        self::assertInstanceOf(\DateTime::class, $now);
        self::assertSame('Europe/Kiev', $now->getTimezone()->getName());
    }

    public function testGetCurrentDatetimeImmutable(): void
    {
        $now = $this->dateTimeHelper->getCurrentDatetimeImmutable();
        self::assertInstanceOf(\DateTimeImmutable::class, $now);

        $now = $this->dateTimeHelper->getCurrentDatetimeImmutable(new \DateTimeZone('Europe/Kiev'));
        self::assertInstanceOf(\DateTimeImmutable::class, $now);
        self::assertSame('Europe/Kiev', $now->getTimezone()->getName());
    }

    public function testGetDatesFromDateRangeAsArrayOfStrings(): void
    {
        $this->dateRange
            ->expects(self::once())
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable('2030-01-01', new \DateTimeZone('UTC')))
        ;
        $this->dateRange
            ->expects(self::once())
            ->method('getTill')
            ->willReturn(new \DateTimeImmutable('2030-01-03', new \DateTimeZone('UTC')))
        ;

        $dates = $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($this->dateRange);

        self::assertSame(['2030-01-01', '2030-01-02', '2030-01-03'], $dates);
    }

    public function testGetDatesFromDateRangeAsArrayOfStringsInCET(): void
    {
        $this->dateRange
            ->expects(self::once())
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable('2030-01-01', new \DateTimeZone('Europe/Berlin')))
        ;
        $this->dateRange
            ->expects(self::once())
            ->method('getTill')
            ->willReturn(new \DateTimeImmutable('2030-01-03', new \DateTimeZone('Europe/Berlin')))
        ;

        $dates = $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($this->dateRange);

        self::assertSame(['2030-01-01', '2030-01-02', '2030-01-03'], $dates);
    }

    public function testGetDatesFromDateRangeAsArrayOfStringsWithOneDay(): void
    {
        $this->dateRange
            ->expects(self::once())
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable('2030-01-01', new \DateTimeZone('UTC')))
        ;
        $this->dateRange
            ->expects(self::once())
            ->method('getTill')
            ->willReturn(new \DateTimeImmutable('2030-01-01', new \DateTimeZone('UTC')))
        ;

        $dates = $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($this->dateRange);

        self::assertSame(['2030-01-01'], $dates);
    }
}
