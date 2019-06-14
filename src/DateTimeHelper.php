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

namespace Fresh\DateTime;

use Fresh\DateTime\Exception\InvalidArgumentException;

/**
 * DateTimeHelper.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeHelper
{
    /**
     * @param \DateTimeInterface $date
     *
     * @throws InvalidArgumentException
     *
     * @return \DateTimeImmutable
     */
    public static function convertDateTimeToImmutable(\DateTimeInterface $date): \DateTimeImmutable
    {
        if (!$date instanceof \DateTimeImmutable) {
            if ($date instanceof \DateTime) {
                return \DateTimeImmutable::createFromMutable($date);
            }

            throw new InvalidArgumentException(\sprintf('Date object is not instance of %s', \DateTime::class));
        }

        return $date;
    }
}
