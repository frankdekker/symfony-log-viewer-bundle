<?php
declare(strict_types=1);

namespace FD\LogViewer\StreamReader;

use FD\LogViewer\Entity\Output\DirectionEnum;
use RuntimeException;
use SplFileInfo;

class StreamReaderFactory
{
    public function createForFile(SplFileInfo $file, DirectionEnum $direction, ?int $offset): AbstractStreamReader
    {
        $fileHandle = @fopen($file->getPathname(), 'rb');
        if ($fileHandle === false) {
            throw new RuntimeException(sprintf('Could not open file "%s".', $file->getPathname()));
        }

        if ($direction === DirectionEnum::Desc) {
            return new ReverseStreamReader($fileHandle, $offset);
        }

        return new ForwardStreamReader($fileHandle, $offset ?? 0);
    }
}
