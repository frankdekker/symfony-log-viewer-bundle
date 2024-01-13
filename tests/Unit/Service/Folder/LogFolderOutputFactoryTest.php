<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Folder;

use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Entity\Output\LogFileOutput;
use FD\LogViewer\Entity\Output\LogFolderOutput;
use FD\LogViewer\Service\Folder\LogFolderOutputFactory;
use FD\LogViewer\Tests\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFolderOutputFactory::class)]
class LogFolderOutputFactoryTest extends TestCase
{
    use TestEntityTrait;

    private LogFolderOutputFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new LogFolderOutputFactory();
    }

    public function testCreateFromFolders(): void
    {
        $config     = $this->createLogFileConfig();
        $collection = new LogFolderCollection($config);
        $logFolder  = new LogFolder('folderId', 'path', 'relative', 11111, 22222, $collection);
        $collection->getOrAdd('foo', static fn() => $logFolder);
        $logFile = new LogFile('fileId', 'path', 'relative', 11111, 22222, 33333, $logFolder);
        $logFolder->addFile($logFile);

        $expectedFile   = new LogFileOutput('fileId', 'path', '10.85 kB', 22222, 33333, false, false);
        $expectedFolder = new LogFolderOutput('folderId', 'name/relative', false, false, 22222, [$expectedFile]);

        $result = $this->factory->createFromFolders($collection);
        static::assertEquals([$expectedFolder], $result);
    }
}
