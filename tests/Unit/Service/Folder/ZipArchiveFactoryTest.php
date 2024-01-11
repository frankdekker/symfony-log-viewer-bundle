<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Folder;

use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\Folder\ZipArchiveFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ZipArchiveFactory::class)]
class ZipArchiveFactoryTest extends TestCase
{
    private ZipArchiveFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new ZipArchiveFactory();
    }

    public function testCreateFromFolder(): void
    {
        $folder = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $this->createMock(LogFolderCollection::class));
        $file   = new LogFile('identifier', __FILE__, 'relative', 11111, 22222, 33333, $folder);
        $folder->addFile($file);

        $zip = $this->factory->createFromFolder($folder);
        static::assertFileExists($zip->getPathname());
    }
}
