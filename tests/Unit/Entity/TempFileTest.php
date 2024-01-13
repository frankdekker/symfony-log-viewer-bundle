<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity;

use FD\LogViewer\Entity\TempFile;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TempFile::class)]
class TempFileTest extends TestCase
{
    public function testConstruct(): void
    {
        $file = new TempFile();
        $path = $file->getPathname();
        static::assertFileExists($path);

        // should be deleted on destruct
        unset($file);
        static::assertFileDoesNotExist($path);
    }
}
