<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class DownloadFolderTest extends AbstractFunctionalTestCase
{
    public function testInvoke(): void
    {
        if (extension_loaded('zip') === false) {
            static::markTestSkipped('The zip extension is not available.');
        }

        $this->client->request('GET', '/log-viewer/api/folder/' . self::getShortMd5('resources/Functional/log'));
        static::assertResponseIsSuccessful();

        static::assertSame('attachment; filename=log.zip', $this->client->getResponse()->headers->get('Content-Disposition'));
    }
}
