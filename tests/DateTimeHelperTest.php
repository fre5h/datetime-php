<?php
/*
 * This file is part of the DateTime library
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Fresh\DateTime;

use Fresh\DateTime\DateTimeHelper;
use PHPUnit\Framework\TestCase;

/**
 * DateTimeHelperTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class DateTimeHelperTest extends TestCase
{
    public function testImmutableDate(): void
    {
        $date = new \DateTimeImmutable('1970-01-01');
        $processedDate = DateTimeHelper::convertDateTimeToImmutable($date);

        self::assertInstanceOf(\DateTimeImmutable::class, $processedDate);
        self::assertSame($date, $processedDate);
    }

    public function testDateTimeObject(): void
    {
        $date = new \DateTime('1970-01-01');
        $processedDate = DateTimeHelper::convertDateTimeToImmutable($date);

        self::assertInstanceOf(\DateTimeImmutable::class, $processedDate);
        self::assertSame($date->format('Y-m-d'), $processedDate->format('Y-m-d'));
    }
}
