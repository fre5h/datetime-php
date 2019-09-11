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
        $till = new \DateTime('now');

        $dateRange = new DateRange($since, $till);

        self::assertSame($since, $dateRange->getSince());
        self::assertSame($till, $dateRange->getTill());
    }

    public function testSetGetSince(): void
    {
        $since = new \DateTime('yesterday');

        $dateRange = new DateRange(new \DateTime('now'), new \DateTime('now'));
        self::assertNotSame($since, $dateRange->getSince());

        $dateRange->setSince($since);
        self::assertSame($since, $dateRange->getSince());
    }

    public function testSetGetTill(): void
    {
        $till = new \DateTime('tomorrow');

        $dateRange = new DateRange(new \DateTime('now'), new \DateTime('now'));
        self::assertNotSame($till, $dateRange->getTill());

        $dateRange->setTill($till);
        self::assertSame($till, $dateRange->getTill());
    }

    public function testAssertSameTimezones(): void
    {
        $dateRange = new DateRange(
            new \DateTime('now', new \DateTimeZone('Europe/Kiev')),
            new \DateTime('now', new \DateTimeZone('Europe/Warsaw'))
        );

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Date range has different timezones');

        $dateRange->assertSameTimezones();
    }
}
