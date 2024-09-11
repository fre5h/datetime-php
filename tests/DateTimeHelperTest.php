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

use Fresh\DateTime\DateRangeInterface;
use Fresh\DateTime\DateTimeHelper;
use Fresh\DateTime\DateTimeHelperInterface;
use Fresh\DateTime\Exception\InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * DateTimeHelperTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeHelperTest extends TestCase
{
    private DateRangeInterface&MockObject $dateRange;
    private DateTimeHelper $dateTimeHelper;

    protected function setUp(): void
    {
        $this->dateRange = $this->createMock(DateRangeInterface::class);
        $this->dateTimeHelper = new DateTimeHelper();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dateRange,
            $this->dateTimeHelper,
        );
    }

    #[Test]
    public function constructor(): void
    {
        $this->assertInstanceOf(DateTimeHelperInterface::class, $this->dateTimeHelper);
    }

    #[Test]
    public function createDateTimeZone(): void
    {
        $timezone = $this->dateTimeHelper->createDateTimeZone();
        $this->assertInstanceOf(\DateTimeZone::class, $timezone);
        $this->assertSame('UTC', $timezone->getName());

        $timezone = $this->dateTimeHelper->createDateTimeZone('Europe/Kiev');
        $this->assertInstanceOf(\DateTimeZone::class, $timezone);
    }

    #[Test]
    public function createDateTimeZoneUtc(): void
    {
        $timezone1 = $this->dateTimeHelper->createDateTimeZoneUtc();
        $this->assertInstanceOf(\DateTimeZone::class, $timezone1);
        $this->assertSame('UTC', $timezone1->getName());

        // Second call hits cache
        $timezone2 = $this->dateTimeHelper->createDateTimeZoneUtc();
        $this->assertInstanceOf(\DateTimeZone::class, $timezone2);
        $this->assertSame('UTC', $timezone2->getName());
        $this->assertEquals($timezone1, $timezone2);
    }

    #[Test]
    public function getCurrentTimestamp(): void
    {
        $timestamp = $this->dateTimeHelper->getCurrentTimestamp();
        $this->assertIsInt($timestamp);
    }

    #[Test]
    public function getCurrentDatetime(): void
    {
        $now = $this->dateTimeHelper->getCurrentDatetime();
        $this->assertInstanceOf(\DateTime::class, $now);

        $now = $this->dateTimeHelper->getCurrentDatetime(new \DateTimeZone('Europe/Kiev'));
        $this->assertInstanceOf(\DateTime::class, $now);
        $this->assertSame('Europe/Kiev', $now->getTimezone()->getName());
    }

    #[Test]
    public function getCurrentDatetimeUtc(): void
    {
        $now = $this->dateTimeHelper->getCurrentDatetimeUtc();
        $this->assertInstanceOf(\DateTime::class, $now);
        $this->assertSame('UTC', $now->getTimezone()->getName());
    }

    #[Test]
    public function getCurrentDatetimeImmutable(): void
    {
        $now = $this->dateTimeHelper->getCurrentDatetimeImmutable();
        $this->assertInstanceOf(\DateTimeImmutable::class, $now);

        $now = $this->dateTimeHelper->getCurrentDatetimeImmutable(new \DateTimeZone('Europe/Kiev'));
        $this->assertInstanceOf(\DateTimeImmutable::class, $now);
        $this->assertSame('Europe/Kiev', $now->getTimezone()->getName());
    }

    #[Test]
    public function now(): void
    {
        $now = $this->dateTimeHelper->now();
        $this->assertInstanceOf(\DateTimeImmutable::class, $now);
        $this->assertSame('UTC', $now->getTimezone()->getName());
    }

    #[Test]
    public function getCurrentDatetimeImmutableUtc(): void
    {
        $now = $this->dateTimeHelper->getCurrentDatetimeImmutableUtc();
        $this->assertInstanceOf(\DateTimeImmutable::class, $now);
        $this->assertSame('UTC', $now->getTimezone()->getName());
    }

    #[Test]
    #[DataProvider('dataProviderForTestGetDatesFromDateRangeAsArrayOfStrings')]
    public function getDatesFromDateRangeAsArrayOfObjects(string $timeZoneName, string $since, string $till, array $expectedDates): void
    {
        $this->dateRange
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable($since, new \DateTimeZone($timeZoneName)))
        ;
        $this->dateRange
            ->method('getTill')
            ->willReturn(new \DateTimeImmutable($till, new \DateTimeZone($timeZoneName)))
        ;

        $datesAsObjects = $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfObjects($this->dateRange);
        $dates = \array_map(
            function (\DateTimeInterface $date) {
                return $date->format('Y-m-d');
            },
            $datesAsObjects
        );

        $this->assertSame($expectedDates, $dates);
    }

    #[Test]
    #[DataProvider('dataProviderForTestGetDatesFromDateRangeAsArrayOfStrings')]
    public function getDatesFromDateRangeAsArrayOfStrings(string $timeZoneName, string $since, string $till, array $expectedDates): void
    {
        $this->dateRange
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable($since, new \DateTimeZone($timeZoneName)))
        ;
        $this->dateRange
            ->method('getTill')
            ->willReturn(new \DateTimeImmutable($till, new \DateTimeZone($timeZoneName)))
        ;

        $dates = $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($this->dateRange);

        $this->assertSame($expectedDates, $dates);
    }

    public static function dataProviderForTestGetDatesFromDateRangeAsArrayOfStrings(): \Generator
    {
        yield 'UTC three days' => [
            'timeZoneName' => 'UTC',
            'since' => '2000-01-01',
            'till' => '2000-01-03',
            'expectedDates' => ['2000-01-01', '2000-01-02', '2000-01-03'],
        ];

        yield 'CET three days' => [
            'timeZoneName' => 'Europe/Berlin',
            'since' => '2000-01-01',
            'till' => '2000-01-03',
            'expectedDates' => ['2000-01-01', '2000-01-02', '2000-01-03'],
        ];

        yield 'CET three days cross months' => [
            'timeZoneName' => 'Europe/Berlin',
            'since' => '2000-02-28',
            'till' => '2000-03-02',
            'expectedDates' => ['2000-02-28', '2000-02-29', '2000-03-01', '2000-03-02'],
        ];

        yield 'UTC one day' => [
            'timeZoneName' => 'UTC',
            'since' => '2000-01-01',
            'till' => '2000-01-01',
            'expectedDates' => ['2000-01-01'],
        ];

        yield 'CET one day' => [
            'timeZoneName' => 'Europe/Berlin',
            'since' => '2000-01-01',
            'till' => '2000-01-01',
            'expectedDates' => ['2000-01-01'],
        ];
    }

    #[Test]
    public function datesCache(): void
    {
        $expectedDates = ['2000-01-01', '2000-01-02', '2000-01-03'];

        $this->dateRange
            ->expects(self::exactly(3)) // 2 times for first call and only 1 for next call
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable('2000-01-01', new \DateTimeZone('UTC')))
        ;
        $this->dateRange
            ->expects(self::exactly(3)) // 2 times for first call and only 1 for next call
            ->method('getTill')
            ->willReturn(new \DateTimeImmutable('2000-01-03', new \DateTimeZone('UTC')))
        ;

        $dates1 = $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($this->dateRange);
        $dates2 = $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($this->dateRange);

        $this->assertSame($expectedDates, $dates1);
        $this->assertSame($expectedDates, $dates2);
    }

    #[Test]
    public function createDateTimeFromFormatWithDefaultTimezone(): void
    {
        $dateTime = $this->dateTimeHelper->createDateTimeFromFormat(dateTimeAsString: '2000-01-01 00:00:00');
        $this->assertInstanceOf(\DateTime::class, $dateTime);
        $this->assertSame('UTC', $dateTime->getTimezone()->getName());
    }

    #[Test]
    public function createDateTimeFromFormatWithCustomTimezone(): void
    {
        $dateTime = $this->dateTimeHelper->createDateTimeFromFormat(dateTimeAsString: '2000-01-01 00:00:00', timeZone: new \DateTimeZone('Europe/Berlin'));
        $this->assertInstanceOf(\DateTime::class, $dateTime);
        $this->assertSame('Europe/Berlin', $dateTime->getTimezone()->getName());
    }

    #[Test]
    public function createDateTimeFromFormatWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not create a \DateTime object from string "fake" from format "Y-m-d H:i:s".');


        $this->dateTimeHelper->createDateTimeFromFormat(dateTimeAsString: 'fake');
    }

    #[Test]
    public function createDateTimeImmutableFromFormatWithDefaultTimezone(): void
    {
        $dateTime = $this->dateTimeHelper->createDateTimeImmutableFromFormat(dateTimeAsString: '2000-01-01 00:00:00');
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTime);
        $this->assertSame('UTC', $dateTime->getTimezone()->getName());
    }

    #[Test]
    public function createDateTimeImmutableFromFormatWithCustomTimezone(): void
    {
        $dateTime = $this->dateTimeHelper->createDateTimeImmutableFromFormat(dateTimeAsString: '2000-01-01 00:00:00', timeZone: new \DateTimeZone('Europe/Berlin'));
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTime);
        $this->assertSame('Europe/Berlin', $dateTime->getTimezone()->getName());
    }

    #[Test]
    public function createDateTimeImmutableFromFormatWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not create a \DateTime object from string "fake" from format "Y-m-d H:i:s".');


        $this->dateTimeHelper->createDateTimeImmutableFromFormat(dateTimeAsString: 'fake');
    }
}
