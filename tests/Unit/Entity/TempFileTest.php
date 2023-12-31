<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Entity;

use FD\SymfonyLogViewerBundle\Entity\TempFile;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TempFile::class)]
class TempFileTest extends TestCase
{
    public function testConstruct(): void
    {
        $file = new TempFile(static fn($callback) => $callback());
        static::assertSame('tmp', $file->getExtension());

        // should be immediately deleted
        static::assertFalse($file->isFile());
    }
}
