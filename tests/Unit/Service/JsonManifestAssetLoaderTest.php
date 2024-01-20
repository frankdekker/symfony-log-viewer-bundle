<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service;

use FD\LogViewer\Service\JsonManifestAssetLoader;
use JsonException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(JsonManifestAssetLoader::class)]
class JsonManifestAssetLoaderTest extends TestCase
{
    private JsonManifestAssetLoader $strategy;
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();
        $manifest       = json_encode(['/path/to/file' => ['file' => 'file']]);
        $this->path     = vfsStream::setup('root', 0777, ['manifest.json' => $manifest])->url();
        $this->strategy = new JsonManifestAssetLoader($this->path . '/manifest.json');
    }

    /**
     * @throws JsonException
     */
    public function testGetUrlExistingFile(): void
    {
        static::assertSame('/bundles/fdlogviewer/file', $this->strategy->getUrl('/path/to/file'));
    }

    /**
     * @throws JsonException
     */
    public function testGetUrlMissingManifestFile(): void
    {
        $strategy = new JsonManifestAssetLoader('foobar');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Asset manifest file does not exist:');
        $strategy->getUrl('foobar');
    }

    /**
     * @throws JsonException
     */
    public function testGetUrlNonReadableManifest(): void
    {
        chmod($this->path . '/manifest.json', 0000);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Asset manifest file is not readable:');
        $this->strategy->getUrl('foobar');
    }

    /**
     * @throws JsonException
     */
    public function testGetUrlFailed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Asset file does not exist:');
        $this->strategy->getUrl('foobar');
    }
}
