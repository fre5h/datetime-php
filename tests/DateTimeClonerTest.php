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
use PHPUnit\Framework\TestCase;

/**
 * DateTimeClonerTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeClonerTest extends TestCase
{
    public function testCloneIntoDateTime(): void
    {
        $datetime = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
        $clonedDateTime = DateTimeCloner::cloneIntoDateTime($datetime);

        self::assertInstanceOf(\DateTime::class, $clonedDateTime);
        self::assertSame($datetime->getTimezone()->getName(), $clonedDateTime->getTimezone()->getName());
        self::assertSame($datetime->format('Y-m-d'), $clonedDateTime->format('Y-m-d'));
    }

    public function testCloneIntoDateTimeWithException(): void
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

    public function testCloneIntoDateTimeImmutable(): void
    {
        $datetime = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
        $cloneIntoDateTimeImmutable = DateTimeCloner::cloneIntoDateTimeImmutable($datetime);

        self::assertInstanceOf(\DateTimeImmutable::class, $cloneIntoDateTimeImmutable);
        self::assertSame($datetime->getTimezone()->getName(), $cloneIntoDateTimeImmutable->getTimezone()->getName());
        self::assertSame($datetime->format('Y-m-d'), $cloneIntoDateTimeImmutable->format('Y-m-d'));
    }

    public function testCloneIntoDateTimeImmutableWithException(): void
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
