<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Entity;

use DigitalRevolution\AccessorPairConstraint\AccessorPairAsserter;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFolder::class)]
class LogFolderTest extends TestCase
{
    use AccessorPairAsserter;

    public function testAccessorPairs(): void
    {
        static::assertAccessorPairs(LogFolder::class);
    }
}
