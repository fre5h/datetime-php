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

use Fresh\DateTime\DateTimeRange;
use Fresh\DateTime\DateTimeRangeInterface;
use Fresh\DateTime\Exception\LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * DateTimeRangeTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeRangeTest extends TestCase
{
    #[Test]
    public function constructor(): void
    {
        $since = new \DateTime('now');
        $till = new \DateTime('tomorrow');

        $dateTimeRange = new DateTimeRange($since, $till);

        $this->assertInstanceOf(DateTimeRangeInterface::class, $dateTimeRange);
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTimeRange->getSince());
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTimeRange->getTill());
        $this->assertSame($since->format('Y-m-d H:i:s'), $dateTimeRange->getSince()->format('Y-m-d H:i:s'));
        $this->assertSame($till->format('Y-m-d H:i:s'), $dateTimeRange->getTill()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function constructorWithExceptionForDifferentTimezones(): void
    {
        $since = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
        $till = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Datetimes have different timezones');

        new DateTimeRange($since, $till);
    }

    #[Test]
    public function getTimezone(): void
    {
        $since = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
        $till = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));

        $dateTimeRange = new DateTimeRange($since, $till);

        $this->assertEquals('Europe/Kiev', $dateTimeRange->getTimezone()->getName());
    }

    #[Test]
    public function getTimezoneName(): void
    {
        $since = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
        $till = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));

        $dateTimeRange = new DateTimeRange($since, $till);

        $this->assertEquals('Europe/Kiev', $dateTimeRange->getTimezoneName());
    }

    #[Test]
    public function isEqual(): void
    {
        $dateTimeRange1 = new DateTimeRange(
            new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
            new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
        );
        $dateTimeRange2 = new DateTimeRange(
            new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
            new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
        );
        $dateTimeRange3 = new DateTimeRange(
            new \DateTime('2000-01-01 12:00:00', new \DateTimeZone('UTC')),
            new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
        );
        $dateTimeRange4 = new DateTimeRange(
            new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
            new \DateTime('2000-01-01 20:00:00', new \DateTimeZone('UTC'))
        );
        $dateTimeRange5 = new DateTimeRange(
            new \DateTime('2000-01-01 12:00:00', new \DateTimeZone('UTC')),
            new \DateTime('2000-01-01 21:00:00', new \DateTimeZone('UTC'))
        );
        $dateTimeRange6 = new DateTimeRange(
            new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('Europe/Kiev')),
            new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('Europe/Kiev'))
        );

        $this->assertTrue($dateTimeRange1->isEqual($dateTimeRange2), 'Since/till timezones are same, time is same');
        $this->assertFalse($dateTimeRange1->isEqual($dateTimeRange3), 'Since/till timezones are same, since time is different');
        $this->assertFalse($dateTimeRange1->isEqual($dateTimeRange4), 'Since/till timezones are same, till time is different');
        $this->assertFalse($dateTimeRange1->isEqual($dateTimeRange5), 'Since/till timezones are same, since/till time is different');
        $this->assertFalse($dateTimeRange1->isEqual($dateTimeRange6), 'Since/till timezones are different');
    }

    #[Test]
    #[DataProvider('dataProviderForTestIntersectsSameDates')]
    #[DataProvider('dataProviderForTestIntersectsDifferentDates')]
    public function intersects(DateTimeRange $dateTimeRange1, DateTimeRange $dateTimeRange2, bool $intersects): void
    {
        $this->assertSame($intersects, $dateTimeRange1->intersects($dateTimeRange2));
    }

    public static function dataProviderForTestIntersectsSameDates(): iterable
    {
        // <time of date range 1>
        // [time of date range 2]

        // [2000-01-01 14:30] < 2000-01-01 14:45> <2000-01-01 15:15> [2000-01-01 15:30]
        yield 'date range 1 is fully inside date range 2 (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:30:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:30:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <2000-01-01 14:30> [2000-01-01 14:45] [2000-01-01 15:15] <2000-01-01 15:30>
        yield 'date range 2 is fully inside date range 1 (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:30:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:30:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // [2000-01-01 14:45] <2000-01-01 15:00> [2000-01-01 15:05] <2000-01-01 15:15>
        yield 'end of date range 2 intersects with start of date range 1 (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:05:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <2000-01-01 14:45> [2000-01-01 15:00] <2000-01-01 15:05> [2000-01-01 15:15]
        yield 'end of date range 1 intersects with start of date range 2 (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:05:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <2000-01-01 14:45> [2000-01-01 14:45] <2000-01-01 15:05> [2000-01-01 15:05]
        yield 'date range 1 equals date range 2 (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:05:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:05:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <2000-01-01 14:45> <2000-01-01 15:00> [2000-01-01 15:00] [2000-01-01 15:15]
        yield 'date range 1 before date range 2 without gap (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];

        // <2000-01-01 14:45> <2000-01-01 15:00> ... [2000-01-01 20:00] [2000-01-01 20:15]
        yield 'date range 1 before date range 2 with gap (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 20:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 20:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];

        // [2000-01-01 14:45] [2000-01-01 15:00] <2000-01-01 15:00> <2000-01-01 15:15>
        yield 'date range 2 before date range 1 without gap (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];

        // [2000-01-01 14:45] [2000-01-01 15:00] ... <2000-01-01 20:00> <2000-01-01 20:15>
        yield 'date range 2 before date range 1 with gap (same dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 20:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 20:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];
    }

    public static function dataProviderForTestIntersectsDifferentDates(): iterable
    {
        // <time of date range 1>
        // [time of date range 2]

        // [2000-01-01 14:30] <2000-01-02 14:45> <2000-01-02 15:15> [2000-01-03 15:30]
        yield 'date range 1 is fully inside date range 2 (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-02 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-02 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:30:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-03 15:30:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <2000-01-01 14:30> [2000-01-02 14:45] [2000-01-02 15:15] <2000-01-03 15:30>
        yield 'date range 2 is fully inside date range 1 (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:30:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-03 15:30:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-02 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-02 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // [2000-01-01 14:45] <2000-01-02 15:00> [2000-01-02 15:05] <2000-01-03 15:15>
        yield 'end of date range 2 intersects with start of date range 1 (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-02 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-03 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-02 15:05:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <2000-01-01 14:45> [2000-01-02 15:00] <2000-01-02 15:05> [2000-01-03 15:15]
        yield 'end of date range 1 intersects with start of date range 2 (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-02 15:05:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-02 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-03 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <2000-01-01 14:45> [2000-01-02 14:45] <2000-01-02 15:05> [2000-01-03 15:05]
        yield 'date range 1 equals date range 2 (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-02 15:05:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-02 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-03 15:05:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <2000-01-01 14:45> <2000-01-01 15:00> [2000-01-02 15:00] [2000-01-02 15:15]
        yield 'date range 1 before date range 2 without gap (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-02 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-02 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];

        // <2000-01-01 14:45> <2000-01-01 15:00> ... [2000-01-02 20:00] [2000-01-02 20:15]
        yield 'date range 1 before date range 2 with gap (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-02-01 20:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-02-01 20:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];

        // [2000-01-01 14:45] [2000-01-01 15:00] <2000-01-02 15:00> <2000-01-02 15:15>
        yield 'date range 2 before date range 1 without gap (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-02 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-02 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];

        // [2000-01-01 14:45] [2000-01-01 15:00] ... <2000-01-02 20:00> <2000-01-02 20:15>
        yield 'date range 2 before date range 1 with gap (different dates)' => [
            'date_range_1' => new DateTimeRange(
                new \DateTime('2000-01-02 20:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-02 20:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateTimeRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];
    }

    #[Test]
    public function intersectsWithExceptionForDifferentTimezones(): void
    {
        $dateRange1 = new DateTimeRange(
            new \DateTime('2000-01-01 19:00:00', new \DateTimeZone('UTC')),
            new \DateTime('2000-01-01 21:00:00', new \DateTimeZone('UTC'))
        );
        $dateRange2 = new DateTimeRange(
            new \DateTime('2000-01-01 21:00:00', new \DateTimeZone('Europe/Kiev')),
            new \DateTime('2000-01-01 23:00:00', new \DateTimeZone('Europe/Kiev'))
        );

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Timezones of datetime ranges are different');

        $dateRange1->intersects($dateRange2);
    }
}
