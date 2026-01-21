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
        $output = new LogFileOutput('identifier', 'name', 'sizeFormatted', 0, 0, true, true, true);

        static::assertSame(
            [
                'identifier'     => 'identifier',
                'name'           => 'name',
                'size_formatted' => 'sizeFormatted',
                'open'           => true,
                'can_download'   => true,
                'can_delete'     => true
            ],
            $output->jsonSerialize()
        );
    }
}
