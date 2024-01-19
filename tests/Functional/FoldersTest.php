<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class FoldersTest extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testInvoke(): void
    {
        $this->client->request('GET', '/log-viewer/api/folders');
        static::assertResponseIsSuccessful();

        $data = static::getJsonFromResponse($this->client->getResponse());
        static::assertSame('test.log', $data[0]['files'][0]['name']);
    }
}
