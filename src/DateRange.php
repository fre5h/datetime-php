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

/**
 * DateRange.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class DateRange
{
    /** @var \DateTimeInterface */
    private $since;

    /** @var \DateTimeInterface */
    private $till;

    /**
     * @param \DateTimeInterface $since
     * @param \DateTimeInterface $till
     */
    public function __construct(\DateTimeInterface $since, \DateTimeInterface $till)
    {
        $this->since = $since;
        $this->till = $till;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSince(): \DateTimeInterface
    {
        return $this->since;
    }

    /**
     * @param \DateTimeInterface $since
     *
     * @return $this
     */
    public function setSince(\DateTimeInterface $since): self
    {
        $this->since = $since;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTill(): \DateTimeInterface
    {
        return $this->till;
    }

    /**
     * @param \DateTimeInterface $till
     *
     * @return $this
     */
    public function setTill(\DateTimeInterface $till): self
    {
        $this->till = $till;

        return $this;
    }
}
