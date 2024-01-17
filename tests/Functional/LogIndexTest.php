<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class LogIndexTest extends AbstractFunctionalTestCase
{
    public function testInvoke(): void
    {
        $this->client->request('GET', '/log-viewer/');
        static::assertResponseIsSuccessful();
    }
}
