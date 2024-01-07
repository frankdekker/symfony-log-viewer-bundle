<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service\Folder;

use FD\SymfonyLogViewerBundle\Controller\DownloadFileController;
use FD\SymfonyLogViewerBundle\Controller\DownloadFolderController;
use FD\SymfonyLogViewerBundle\Entity\Config\FinderConfig;
use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFileOutput;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFolderOutput;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderOutputFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function DR\PHPUnitExtensions\Mock\consecutive;

#[CoversClass(LogFolderOutputFactory::class)]
class LogFolderOutputFactoryTest extends TestCase
{
    private UrlGeneratorInterface&MockObject $urlGenerator;
    private LogFolderOutputFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->factory      = new LogFolderOutputFactory($this->urlGenerator);
    }

    public function testCreateFromFolders(): void
    {
        $config     = new LogFilesConfig('name', 'type', 'name', $this->createMock(FinderConfig::class), true, null, '', '');
        $collection = new LogFolderCollection($config);
        $logFolder  = new LogFolder('folderId', 'path', 'relative', 11111, 22222, $collection);
        $collection->getOrAdd('foo', static fn() => $logFolder);
        $logFile = new LogFile('fileId', 'path', 'relative', 11111, 22222, 33333, $logFolder);
        $logFolder->addFile($logFile);

        $this->urlGenerator
            ->expects(self::exactly(2))
            ->method('generate')
            ->with(
                ...consecutive(
                       [DownloadFolderController::class, ['identifier' => 'folderId']],
                       [DownloadFileController::class, ['identifier' => 'fileId']]
                   )
            )
            ->willReturn('folder-url', 'file-url');

        $expectedFile   = new LogFileOutput('fileId', 'path', '10.85 kB', 'file-url', 22222, 33333, true);
        $expectedFolder = new LogFolderOutput('folderId', 'name/relative', 'folder-url', true, 22222, [$expectedFile]);

        $result = $this->factory->createFromFolders($collection);
        static::assertEquals([$expectedFolder], $result);
    }
}
