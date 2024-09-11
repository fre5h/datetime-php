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
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * DateRangeTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateRangeTest extends TestCase
{
    #[Test]
    public function constructor(): void
    {
        $since = new \DateTime('now');
        $till = new \DateTime('tomorrow');

        $dateRange = new DateRange($since, $till);

        $this->assertInstanceOf(DateRangeInterface::class, $dateRange);
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateRange->getSince());
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateRange->getTill());
        $this->assertSame($since->format('Y-m-d'), $dateRange->getSince()->format('Y-m-d'));
        $this->assertSame($till->format('Y-m-d'), $dateRange->getTill()->format('Y-m-d'));
    }

    #[Test]
    public function constructorWithExceptionForDifferentTimezones(): void
    {
        $since = new \DateTime('now', new \DateTimeZone('Europe/Kyiv'));
        $till = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Dates have different timezones');

        new DateRange($since, $till);
    }

    #[Test]
    public function isEqual(): void
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
            new \DateTime('now', new \DateTimeZone('Europe/Kyiv')),
            new \DateTime('tomorrow', new \DateTimeZone('Europe/Kyiv'))
        );

        $this->assertTrue($dateRange1->isEqual($dateRange2), 'Since/till timezones are same, time is same');
        $this->assertFalse($dateRange1->isEqual($dateRange3), 'Since/till timezones are same, since time is different');
        $this->assertFalse($dateRange1->isEqual($dateRange4), 'Since/till timezones are same, till time is different');
        $this->assertFalse($dateRange1->isEqual($dateRange5), 'Since/till timezones are same, since/till time is different');
        $this->assertFalse($dateRange1->isEqual($dateRange6), 'Since/till timezones are different');
    }
}
