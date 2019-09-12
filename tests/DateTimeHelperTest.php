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
use Fresh\DateTime\Exception\UnexpectedValueException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * DateTimeHelperTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeHelperTest extends TestCase
{
    /** @var DateRangeInterface|MockObject */
    private $dateRange;

    /** @var DateTimeHelper */
    private $dateTimeHelper;

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

    public function testConstructor(): void
    {
        self::assertInstanceOf(DateTimeHelperInterface::class, $this->dateTimeHelper);
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

    /** @dataProvider dataProviderForTestGetDatesFromDateRangeAsArrayOfStrings */
    public function testGetDatesFromDateRangeAsArrayOfObjects(string $timeZoneName, string $since, string $till, array $expectedDates): void
    {
        $this->dateRange
            ->expects(self::any())
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable($since, new \DateTimeZone($timeZoneName)))
        ;
        $this->dateRange
            ->expects(self::any())
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

        self::assertSame($expectedDates, $dates);
    }

    /** @dataProvider dataProviderForTestGetDatesFromDateRangeAsArrayOfStrings */
    public function testGetDatesFromDateRangeAsArrayOfStrings(string $timeZoneName, string $since, string $till, array $expectedDates): void
    {
        $this->dateRange
            ->expects(self::any())
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable($since, new \DateTimeZone($timeZoneName)))
        ;
        $this->dateRange
            ->expects(self::any())
            ->method('getTill')
            ->willReturn(new \DateTimeImmutable($till, new \DateTimeZone($timeZoneName)))
        ;

        $dates = $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfStrings($this->dateRange);

        self::assertSame($expectedDates, $dates);
    }

    public static function dataProviderForTestGetDatesFromDateRangeAsArrayOfStrings(): \Generator
    {
        yield 'UTC three days' => [
            'timezone_name' => 'UTC',
            'since' => '2000-01-01',
            'till' => '2000-01-03',
            'dates' => ['2000-01-01', '2000-01-02', '2000-01-03'],
        ];

        yield 'CET three days' => [
            'timezone_name' => 'Europe/Berlin',
            'since' => '2000-01-01',
            'till' => '2000-01-03',
            'dates' => ['2000-01-01', '2000-01-02', '2000-01-03'],
        ];

        yield 'CET three days cross months' => [
            'timezone_name' => 'Europe/Berlin',
            'since' => '2000-02-28',
            'till' => '2000-03-02',
            'dates' => ['2000-02-28', '2000-02-29', '2000-03-01', '2000-03-02'],
        ];

        yield 'UTC one day' => [
            'timezone_name' => 'UTC',
            'since' => '2000-01-01',
            'till' => '2000-01-01',
            'dates' => ['2000-01-01'],
        ];

        yield 'CET one day' => [
            'timezone_name' => 'Europe/Berlin',
            'since' => '2000-01-01',
            'till' => '2000-01-01',
            'dates' => ['2000-01-01'],
        ];
    }

    public function testExceptionOnCloningDates(): void
    {
        $this->dateRange
            ->expects(self::any())
            ->method('getSince')
            ->willReturn(new \DateTimeImmutable('0000-00-00', new \DateTimeZone('UTC')))
        ;
        $this->dateRange
            ->expects(self::any())
            ->method('getTill')
            ->willReturn(new \DateTimeImmutable('0000-00-00', new \DateTimeZone('UTC')))
        ;

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Could not create DateTime object');

        $this->dateTimeHelper->getDatesFromDateRangeAsArrayOfObjects($this->dateRange);
    }

    public function testDatesCache(): void
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

        self::assertSame($expectedDates, $dates1);
        self::assertSame($expectedDates, $dates2);
    }
}
