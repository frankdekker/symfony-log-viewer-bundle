<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity;

use DigitalRevolution\AccessorPairConstraint\AccessorPairAsserter;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\LogFolderCollection;
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

    public function testUpdateEarliestTimestamp(): void
    {
        $folder = new LogFolder('identifier', 'path', 'relative-path', 555555, 666666, $this->createMock(LogFolderCollection::class));
        $folder->updateEarliestTimestamp(666666);
        static::assertSame(555555, $folder->getEarliestTimestamp());

        $folder->updateEarliestTimestamp(444444);
        static::assertSame(444444, $folder->getEarliestTimestamp());
    }

    public function testUpdateLatestTimestamp(): void
    {
        $folder = new LogFolder('identifier', 'path', 'relative-path', 555555, 666666, $this->createMock(LogFolderCollection::class));
        $folder->updateLatestTimestamp(444444);
        static::assertSame(666666, $folder->getLatestTimestamp());

        $folder->updateLatestTimestamp(777777);
        static::assertSame(777777, $folder->getLatestTimestamp());
    }
}
