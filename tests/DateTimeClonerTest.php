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

use Fresh\DateTime\DateTimeCloner;
use Fresh\DateTime\Exception\UnexpectedValueException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * DateTimeClonerTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeClonerTest extends TestCase
{
    #[Test]
    public function cloneIntoDateTime(): void
    {
        $datetime = new \DateTime('now', new \DateTimeZone('Europe/Kyiv'));
        $clonedDateTime = DateTimeCloner::cloneIntoDateTime($datetime);

        $this->assertInstanceOf(\DateTime::class, $clonedDateTime);
        $this->assertSame($datetime->getTimezone()->getName(), $clonedDateTime->getTimezone()->getName());
        $this->assertSame($datetime->format('Y-m-d'), $clonedDateTime->format('Y-m-d'));
    }

    #[Test]
    public function coneIntoDateTimeWithException(): void
    {
        $datetime = $this->createMock(\DateTime::class);
        $datetime
            ->expects(self::once())
            ->method('format')
            ->willReturn('broken format')
        ;
        $datetime
            ->expects(self::once())
            ->method('getTimezone')
            ->willReturn(false)
        ;

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Could not create DateTime object');

        DateTimeCloner::cloneIntoDateTime($datetime);
    }

    #[Test]
    public function cloneIntoDateTimeImmutable(): void
    {
        $datetime = new \DateTime('now', new \DateTimeZone('Europe/Kyiv'));
        $cloneIntoDateTimeImmutable = DateTimeCloner::cloneIntoDateTimeImmutable($datetime);

        $this->assertInstanceOf(\DateTimeImmutable::class, $cloneIntoDateTimeImmutable);
        $this->assertSame($datetime->getTimezone()->getName(), $cloneIntoDateTimeImmutable->getTimezone()->getName());
        $this->assertSame($datetime->format('Y-m-d'), $cloneIntoDateTimeImmutable->format('Y-m-d'));
    }

    #[Test]
    public function cloneIntoDateTimeImmutableWithException(): void
    {
        $datetime = $this->createMock(\DateTimeImmutable::class);
        $datetime
            ->expects(self::once())
            ->method('format')
            ->willReturn('broken format')
        ;
        $datetime
            ->expects(self::once())
            ->method('getTimezone')
            ->willReturn(false)
        ;

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Could not create DateTimeImmutable object');

        DateTimeCloner::cloneIntoDateTimeImmutable($datetime);
    }
}
