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

namespace Fresh\DateTime\Tests\Exception;

use Fresh\DateTime\Exception\ExceptionInterface;
use Fresh\DateTime\Exception\InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * InvalidArgumentExceptionTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class InvalidArgumentExceptionTest extends TestCase
{
    #[Test]
    public function constructor(): void
    {
        $invalidArgumentException = new InvalidArgumentException();
        $this->assertInstanceOf(\InvalidArgumentException::class, $invalidArgumentException);
        $this->assertInstanceOf(ExceptionInterface::class, $invalidArgumentException);
    }
}
