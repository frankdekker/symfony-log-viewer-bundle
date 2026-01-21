<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Folder;

use FD\LogViewer\Entity\Config\OpenFileConfig;
use FD\LogViewer\Service\Folder\OpenLogFileDecider;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OpenLogFileDecider::class)]
class OpenLogFileDeciderTest extends TestCase
{
    use TestEntityTrait;

    private OpenLogFileDecider $decider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->decider = new OpenLogFileDecider();
    }

    public function testDecideSortedByNewest(): void
    {
        $config  = new OpenFileConfig('*.log', OpenFileConfig::ORDER_NEWEST);
        $folder  = $this->createLogFolder();
        $fileOld = $this->createLogFile(['path' => 'app.log', 'updateTimestamp' => 1000, 'folder' => $folder]);
        $fileNew = $this->createLogFile(['path' => 'dev.log', 'updateTimestamp' => 2000, 'folder' => $folder]);
        $folder->addFile($fileOld);
        $folder->addFile($fileNew);

        $result = $this->decider->decide($config, [$folder]);
        static::assertSame($fileNew, $result);
    }

    public function testDecideSortedByOldest(): void
    {
        $config  = new OpenFileConfig('*.log', OpenFileConfig::ORDER_OLDEST);
        $folder  = $this->createLogFolder();
        $fileOld = $this->createLogFile(['path' => 'app.log', 'updateTimestamp' => 1000, 'folder' => $folder]);
        $fileNew = $this->createLogFile(['path' => 'dev.log', 'updateTimestamp' => 2000, 'folder' => $folder]);
        $folder->addFile($fileOld);
        $folder->addFile($fileNew);

        $result = $this->decider->decide($config, [$folder]);
        static::assertSame($fileOld, $result);
    }

    public function testDecideWithNoMatches(): void
    {
        $config = new OpenFileConfig('*.log', OpenFileConfig::ORDER_NEWEST);
        $folder = $this->createLogFolder();
        $file   = $this->createLogFile(['path' => 'app.txt', 'folder' => $folder]);
        $folder->addFile($file);

        $result = $this->decider->decide($config, [$folder]);
        static::assertNull($result);
    }
}
