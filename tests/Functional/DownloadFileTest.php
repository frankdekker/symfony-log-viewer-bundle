<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class DownloadFileTest extends AbstractFunctionalTestCase
{
    public function testInvoke(): void
    {
        $this->client->request('GET', '/log-viewer/api/file/85d222d3');
        static::assertResponseIsSuccessful();

        static::assertSame('attachment; filename=test.log', $this->client->getResponse()->headers->get('Content-Disposition'));
    }
}
