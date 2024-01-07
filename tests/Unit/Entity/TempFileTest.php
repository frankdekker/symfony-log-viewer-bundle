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
        $file = new TempFile();
        $path = $file->getPathname();

        static::assertSame('tmp', $file->getExtension());
        static::assertFileExists($path);

        // should be deleted on destruct
        unset($file);
        static::assertFileDoesNotExist($path);
    }
}
