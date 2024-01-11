<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Output;

use FD\LogViewer\Entity\Output\LogFileOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFileOutput::class)]
class LogFileOutputTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $output = new LogFileOutput('identifier', 'name', 'sizeFormatted', 'downloadUrl', 0, 0, true);

        static::assertSame(
            [
                'identifier'     => 'identifier',
                'name'           => 'name',
                'size_formatted' => 'sizeFormatted',
                'download_url'   => 'downloadUrl',
                'can_download'   => true,
            ],
            $output->jsonSerialize()
        );
    }
}
