<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Util;

use FD\LogViewer\Util\ExtensionChecker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ExtensionChecker::class)]
class ExtensionCheckerTest extends TestCase
{
    private ExtensionChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checker = new ExtensionChecker();
    }

    public function testReturnsTrueForZipExtension(): void
    {
        static::assertTrue($this->checker->isLoaded('zip'));
    }

    public function testReturnsTrueForZlibExtension(): void
    {
        static::assertTrue($this->checker->isLoaded('zlib'));
    }

    public function testReturnsFalseForUnloadedExtension(): void
    {
        static::assertFalse($this->checker->isLoaded('non_existent_extension'));
    }
}
