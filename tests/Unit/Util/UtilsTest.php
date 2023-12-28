<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Util;

use FD\SymfonyLogViewerBundle\Util\Utils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(Utils::class)]
class UtilsTest extends TestCase
{
    public function testShortMd5(): void
    {
        static::assertSame('3a610852', Utils::shortMd5('foobar foobar foobar foobar'));
    }

    #[TestWith([1024 * 1024 * 1024 * 1024 + 1, '1024.00 GB'])]
    #[TestWith([1024 * 1024 * 1024 + 1, '1.00 GB'])]
    #[TestWith([1024 * 1024 + 1, '1.00 MB'])]
    #[TestWith([1025, '1.00 kB'])]
    #[TestWith([500, '500 bytes'])]
    public function testBytesForHumans(int $bytes, string $expected): void
    {
        static::assertSame($expected, Utils::bytesForHumans($bytes));
    }
}
