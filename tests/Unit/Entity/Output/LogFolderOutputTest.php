<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Output;

use FD\LogViewer\Entity\Output\LogFileOutput;
use FD\LogViewer\Entity\Output\LogFolderOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFolderOutput::class)]
class LogFolderOutputTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $file   = new LogFileOutput('identifier', 'name', 'sizeFormatted', 0, 0, true, true);
        $output = new LogFolderOutput('identifier', 'path', true, true, 111111, [$file]);

        static::assertSame(
            [
                'identifier'   => 'identifier',
                'path'         => 'path',
                'download_url' => 'downloadUrl',
                'files'        => [$file],
                'can_download' => true,
                'can_delete'   => true
            ],
            $output->jsonSerialize()
        );
    }
}
