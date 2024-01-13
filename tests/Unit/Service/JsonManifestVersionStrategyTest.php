<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service;

use FD\LogViewer\Service\JsonManifestVersionStrategy;
use JsonException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(JsonManifestVersionStrategy::class)]
class JsonManifestVersionStrategyTest extends TestCase
{
    private JsonManifestVersionStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $manifest       = json_encode(['/path/to/file' => ['file' => 'file']]);
        $path           = vfsStream::setup('root', 0777, ['manifest.json' => $manifest])->url();
        $this->strategy = new JsonManifestVersionStrategy($path . '/manifest.json');
    }

    /**
     * @throws JsonException
     */
    public function testGetVersionExistingFile(): void
    {
        static::assertSame('bundles/fdlogviewer/file', $this->strategy->getVersion('/path/to/file'));
    }

    /**
     * @throws JsonException
     */
    public function testGetVersionFailed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Asset manifest file does not exist:');
        $this->strategy->getVersion('foobar');
    }
}
