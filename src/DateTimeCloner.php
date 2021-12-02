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

namespace Fresh\DateTime;

use Fresh\DateTime\Exception\UnexpectedValueException;

/**
 * DateTimeCloner.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateTimeCloner
{
    private const DATE_FORMAT_FOR_CLONE = 'Y-m-d H:i:s e';

    /**
     * @param \DateTimeInterface $originalDate
     *
     * @throws UnexpectedValueException
     *
     * @return \DateTime
     */
    public static function cloneIntoDateTime(\DateTimeInterface $originalDate): \DateTime
    {
        $date = \DateTime::createFromFormat(
            self::DATE_FORMAT_FOR_CLONE,
            $originalDate->format(self::DATE_FORMAT_FOR_CLONE),
            $originalDate->getTimezone() ?? null
        );

        if (!$date instanceof \DateTime) {
            throw new UnexpectedValueException(\sprintf('Could not create %s object', \DateTime::class));
        }

        return $date;
    }

    /**
     * @param \DateTimeInterface $originalDate
     *
     * @throws UnexpectedValueException
     *
     * @return \DateTimeImmutable
     */
    public static function cloneIntoDateTimeImmutable(\DateTimeInterface $originalDate): \DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat(
            self::DATE_FORMAT_FOR_CLONE,
            $originalDate->format(self::DATE_FORMAT_FOR_CLONE),
            $originalDate->getTimezone() ?? null
        );

        if (!$date instanceof \DateTimeImmutable) {
            throw new UnexpectedValueException(\sprintf('Could not create %s object', \DateTimeImmutable::class));
        }

        return $date;
    }
}
