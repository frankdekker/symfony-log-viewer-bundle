<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity;

use DigitalRevolution\AccessorPairConstraint\AccessorPairAsserter;
use FD\LogViewer\Entity\LogFile;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFile::class)]
class LogFileTest extends TestCase
{
    use AccessorPairAsserter;

    public function testAccessorPairs(): void
    {
        static::assertAccessorPairs(LogFile::class);
    }
}
