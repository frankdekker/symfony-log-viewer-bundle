<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class LogRecordsTest extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testInvoke(): void
    {
        $this->client->request('GET', '/log-viewer/api/logs?file=85d222d3');
        static::assertResponseIsSuccessful();

        /** @var array{channels: mixed[], levels: mixed[], logs: mixed[]} $data */
        $data = static::responseToArray($this->client->getResponse());
        static::assertCount(2, $data['channels']);
        static::assertCount(2, $data['levels']);
        static::assertCount(3, $data['logs']);
    }
}
