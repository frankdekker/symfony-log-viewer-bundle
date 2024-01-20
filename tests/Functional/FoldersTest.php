<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class FoldersTest extends AbstractFunctionalTestCase
{
    public function testInvoke(): void
    {
        $this->client->request('GET', '/log-viewer/api/folders');
        static::assertResponseIsSuccessful();

        /** @var array<array{files: array<array{name: string}>}> $data */
        $data = static::responseToArray($this->client->getResponse());
        static::assertTrue($data[0]['files'][0]['name']);
        static::assertSame('test.log', $data[0]['files'][0]['name']);
    }
}
