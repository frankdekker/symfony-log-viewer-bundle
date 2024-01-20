<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Filesystem\Filesystem;

#[CoversNothing]
class DeleteFolderTest extends AbstractFunctionalTestCase
{
    private Filesystem&MockObject $filesystem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filesystem = $this->createMock(Filesystem::class);
        static::getContainer()->set(Filesystem::class, $this->filesystem);
    }

    public function testInvoke(): void
    {
        $this->filesystem->expects(self::once())
            ->method('remove')
            ->with(static::callback(static fn(string $path) => str_contains($path, 'log' . DIRECTORY_SEPARATOR . 'test.log')));

        $this->client->request('DELETE', '/log-viewer/api/folder/' . self::getShortMd5('resources/Functional/log'));
        static::assertResponseIsSuccessful();
    }
}
