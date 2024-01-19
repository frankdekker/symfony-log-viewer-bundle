<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use FD\LogViewer\Service\JsonManifestAssetLoader;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\MockObject\MockObject;

#[CoversNothing]
class LogIndexTest extends AbstractFunctionalTestCase
{
    private JsonManifestAssetLoader&MockObject $assetLoader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assetLoader = $this->createMock(JsonManifestAssetLoader::class);
        $this->getContainer()->set(JsonManifestAssetLoader::class, $this->assetLoader);
    }

    public function testInvoke(): void
    {
        $this->assetLoader->expects(self::exactly(2))->method('getUrl')->willReturn('url1', 'url2');

        $this->client->request('GET', '/log-viewer/');
        static::assertResponseIsSuccessful();
    }
}
