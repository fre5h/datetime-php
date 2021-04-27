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
use PHPUnit\Framework\TestCase;

/**
 * DateTimeRangeTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeRangeTest extends TestCase
{
    public function testConstructor(): void
    {
        $since = new \DateTime('now');
        $till = new \DateTime('tomorrow');

        $dateTimeRange = new DateTimeRange($since, $till);

        self::assertInstanceOf(DateTimeRangeInterface::class, $dateTimeRange);
        self::assertInstanceOf(\DateTimeImmutable::class, $dateTimeRange->getSince());
        self::assertInstanceOf(\DateTimeImmutable::class, $dateTimeRange->getTill());
        self::assertSame($since->format('Y-m-d H:i:s'), $dateTimeRange->getSince()->format('Y-m-d H:i:s'));
        self::assertSame($till->format('Y-m-d H:i:s'), $dateTimeRange->getTill()->format('Y-m-d H:i:s'));
    }

    public function testConstructorWithExceptionForDifferentTimezones(): void
    {
        $since = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
        $till = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Datetimes have different timezones');

        new DateTimeRange($since, $till);
    }

    public function testIsEqual(): void
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

        self::assertTrue($dateTimeRange1->isEqual($dateTimeRange2), 'Since/till timezones are same, time is same');
        self::assertFalse($dateTimeRange1->isEqual($dateTimeRange3), 'Since/till timezones are same, since time is different');
        self::assertFalse($dateTimeRange1->isEqual($dateTimeRange4), 'Since/till timezones are same, till time is different');
        self::assertFalse($dateTimeRange1->isEqual($dateTimeRange5), 'Since/till timezones are same, since/till time is different');
        self::assertFalse($dateTimeRange1->isEqual($dateTimeRange6), 'Since/till timezones are different');
    }

    /**
     * @param DateTimeRange $dateTimeRange1
     * @param DateTimeRange $dateTimeRange2
     * @param bool      $intersects
     *
     * @dataProvider dataProviderForTestIntersects
     */
    public function testIntersects(DateTimeRange $dateTimeRange1, DateTimeRange $dateTimeRange2, bool $intersects): void
    {
        self::assertSame($intersects, $dateTimeRange1->intersects($dateTimeRange2));
    }

    public static function dataProviderForTestIntersects(): iterable
    {
        // <time of date range 1>
        // [time of date range 2]

        // [14:30] <14:45> <15:15> [15:30]
        yield 'date range 1 is fully inside date range 2' => [
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

        // <14:30> [14:45] [15:15] <15:30>
        yield 'date range 2 is fully inside date range 1' => [
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

        // [14:45] <15:00> [15:05] <15:15>
        yield 'end of date range 2 intersects with start of date range 1' => [
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

        // <14:45> [15:00] <15:05> [15:15]
        yield 'end of date range 1 intersects with start of date range 2' => [
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

        // <14:45> [14:45] <15:05> [15:05]
        yield 'date range 1 equals date range 2' => [
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

        // <14:45> <15:00> [15:00] [15:15]
        yield 'date range 1 before date range 2 without gap' => [
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

        // <14:45> <15:00> ... [20:00] [20:15]
        yield 'date range 1 before date range 2 with gap' => [
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

        // [14:45] [15:00] <15:00> <15:15>
        yield 'date range 2 before date range 1 without gap' => [
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

        // [14:45] [15:00] <20:00> <20:15>
        yield 'date range 2 before date range 1 with gap' => [
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
}
