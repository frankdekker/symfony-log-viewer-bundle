<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Iterator\StreamReader\AbstractStreamReader;
use FD\SymfonyLogViewerBundle\Iterator\StreamReader\ForwardStreamReader;
use FD\SymfonyLogViewerBundle\Iterator\StreamReader\ReverseStreamReader;
use RuntimeException;
use SplFileInfo;

class StreamReaderFactory
{
    public function createForFile(SplFileInfo $file, DirectionEnum $direction, ?int $offset): AbstractStreamReader
    {
        $fileHandle = fopen($file->getPathname(), 'rb');
        if ($fileHandle === false) {
            throw new RuntimeException(sprintf('Could not open file "%s".', $file->getPathname()));
        }

        if ($direction === DirectionEnum::Desc) {
            return new ReverseStreamReader($fileHandle, $offset);
        }

        return new ForwardStreamReader($fileHandle, $offset ?? 0);
    }
}
