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
        $dateRange1 = new DateRange(new \DateTime('now', new \DateTimeZone('UTC')), new \DateTime('tomorrow', new \DateTimeZone('UTC')));
        $dateRange2 = new DateRange(new \DateTime('now', new \DateTimeZone('UTC')), new \DateTime('tomorrow', new \DateTimeZone('UTC')));
        $dateRange3 = new DateRange(new \DateTime('now', new \DateTimeZone('Europe/Kiev')), new \DateTime('tomorrow', new \DateTimeZone('Europe/Kiev')));
        $dateRange4 = new DateRange(new \DateTime('now', new \DateTimeZone('UTC')), new \DateTime('tomorrow', new \DateTimeZone('Europe/Kiev')));
        $dateRange5 = new DateRange(new \DateTime('now', new \DateTimeZone('Europe/Kiev')), new \DateTime('tomorrow', new \DateTimeZone('UTC')));

        self::assertTrue($dateRange1->isEqual($dateRange2), 'Since/till timezone are same');
        self::assertFalse($dateRange1->isEqual($dateRange3), 'Since/till timezones are different');
        self::assertFalse($dateRange1->isEqual($dateRange4), 'Till timezone is different');
        self::assertFalse($dateRange1->isEqual($dateRange5), 'Since timezone is different');
    }
}
