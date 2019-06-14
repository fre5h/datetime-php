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
     * @return \DateTimeImmutable
     */
    public static function convertDateTimeToImmutable(\DateTimeInterface $date): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('U', (string) $date->getTimestamp(), $date->getTimezone());
    }
}
