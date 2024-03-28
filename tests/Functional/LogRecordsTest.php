<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class LogRecordsTest extends AbstractFunctionalTestCase
{
    public function testInvoke(): void
    {
        $this->client->request('GET', '/log-viewer/api/logs?file=' . self::getShortMd5('resources/Functional/log/test.log'));
        static::assertResponseIsSuccessful();

        /** @var array{logs: mixed[]} $data */
        $data = static::responseToArray($this->client->getResponse());
        static::assertCount(3, $data['logs']);
    }
}
