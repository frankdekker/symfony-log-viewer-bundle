<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Folder;

use FD\LogViewer\Service\Folder\ZipArchiveFactory;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ZipArchiveFactory::class)]
class ZipArchiveFactoryTest extends TestCase
{
    use TestEntityTrait;

    private ZipArchiveFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new ZipArchiveFactory();
    }

    public function testCreateFromFolder(): void
    {
        $folder = $this->createLogFolder();
        $file   = $this->createLogFile(['path' => __FILE__, 'folder' => $folder]);
        $folder->addFile($file);

        $zip = $this->factory->createFromFolder($folder);
        static::assertFileExists($zip->getPathname());
    }
}
