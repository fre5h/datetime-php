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
            'since' => '2030-01-01',
            'till' => '2030-01-03',
            'dates' => ['2030-01-01', '2030-01-02', '2030-01-03'],
        ];

        yield 'CET three days' => [
            'timezone_name' => 'Europe/Berlin',
            'since' => '2030-01-01',
            'till' => '2030-01-03',
            'dates' => ['2030-01-01', '2030-01-02', '2030-01-03'],
        ];

        yield 'UTC one day' => [
            'timezone_name' => 'UTC',
            'since' => '2030-01-01',
            'till' => '2030-01-01',
            'dates' => ['2030-01-01'],
        ];

        yield 'CET one day' => [
            'timezone_name' => 'Europe/Berlin',
            'since' => '2030-01-01',
            'till' => '2030-01-01',
            'dates' => ['2030-01-01'],
        ];
    }
}
