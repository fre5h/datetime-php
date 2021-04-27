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
use Fresh\DateTime\DateRangeInterface;
use Fresh\DateTime\Exception\LogicException;
use PHPUnit\Framework\TestCase;

/**
 * DateRangeTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateRangeTest extends TestCase
{
    public function testConstructor(): void
    {
        $since = new \DateTime('now');
        $till = new \DateTime('tomorrow');

        $dateRange = new DateRange($since, $till);

        self::assertInstanceOf(DateRangeInterface::class, $dateRange);
        self::assertInstanceOf(\DateTimeImmutable::class, $dateRange->getSince());
        self::assertInstanceOf(\DateTimeImmutable::class, $dateRange->getTill());
        self::assertSame($since->format('Y-m-d'), $dateRange->getSince()->format('Y-m-d'));
        self::assertSame($till->format('Y-m-d'), $dateRange->getTill()->format('Y-m-d'));
    }

    public function testConstructorWithExceptionForDifferentTimezones(): void
    {
        $since = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
        $till = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Dates have different timezones');

        new DateRange($since, $till);
    }

    public function testIsEqual(): void
    {
        $dateRange1 = new DateRange(
            new \DateTime('now', new \DateTimeZone('UTC')),
            new \DateTime('tomorrow', new \DateTimeZone('UTC'))
        );
        $dateRange2 = new DateRange(
            new \DateTime('now', new \DateTimeZone('UTC')),
            new \DateTime('tomorrow', new \DateTimeZone('UTC'))
        );
        $dateRange3 = new DateRange(
            new \DateTime('yesterday', new \DateTimeZone('UTC')),
            new \DateTime('tomorrow', new \DateTimeZone('UTC'))
        );
        $dateRange4 = new DateRange(
            new \DateTime('now', new \DateTimeZone('UTC')),
            new \DateTime('now', new \DateTimeZone('UTC'))
        );
        $dateRange5 = new DateRange(
            new \DateTime('yesterday', new \DateTimeZone('UTC')),
            new \DateTime('now', new \DateTimeZone('UTC'))
        );
        $dateRange6 = new DateRange(
            new \DateTime('now', new \DateTimeZone('Europe/Kiev')),
            new \DateTime('tomorrow', new \DateTimeZone('Europe/Kiev'))
        );

        self::assertTrue($dateRange1->isEqual($dateRange2), 'Since/till timezones are same, time is same');
        self::assertFalse($dateRange1->isEqual($dateRange3), 'Since/till timezones are same, since time is different');
        self::assertFalse($dateRange1->isEqual($dateRange4), 'Since/till timezones are same, till time is different');
        self::assertFalse($dateRange1->isEqual($dateRange5), 'Since/till timezones are same, since/till time is different');
        self::assertFalse($dateRange1->isEqual($dateRange6), 'Since/till timezones are different');
    }

    /**
     * @param DateRange $dateRange1
     * @param DateRange $dateRange2
     * @param bool      $intersects
     *
     * @dataProvider dataProviderForTestIntersects
     */
    public function testIntersects(DateRange $dateRange1, DateRange $dateRange2, bool $intersects): void
    {
        self::assertSame($intersects, $dateRange1->intersects($dateRange2));
    }

    public static function dataProviderForTestIntersects(): iterable
    {
        // <time of date range 1>
        // [time of date range 2]

        // [14:30] <14:45> <15:15> [15:30]
        yield 'date range 2 is fully inside date range 1' => [
            'date_range_1' => new DateRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateRange(
                new \DateTime('2000-01-01 14:30:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:30:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <14:30> [14:45] [15:15] <15:30>
        yield 'date range 1 is fully inside date range 2' => [
            'date_range_1' => new DateRange(
                new \DateTime('2000-01-01 14:30:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:30:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // [14:45] <15:00> [15:05] <15:15>
        yield 'end of date range 2 intersects with start of date range 1' => [
            'date_range_1' => new DateRange(
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:05:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <14:45> [15:00] <15:05> [15:15]
        yield 'end of date range 1 intersects with start of date range 2' => [
            'date_range_1' => new DateRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:05:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateRange(
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => true,
        ];

        // <14:45> <15:00> [15:00] [15:15]
        yield 'date range 1 before date range 2' => [
            'date_range_1' => new DateRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateRange(
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];

        // [14:45] [15:00] <15:00> <15:15>
        yield 'date range 2 before date range 1' => [
            'date_range_1' => new DateRange(
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:15:00', new \DateTimeZone('UTC'))
            ),
            'date_range_2' => new DateRange(
                new \DateTime('2000-01-01 14:45:00', new \DateTimeZone('UTC')),
                new \DateTime('2000-01-01 15:00:00', new \DateTimeZone('UTC'))
            ),
            'intersects' => false,
        ];
    }
}
