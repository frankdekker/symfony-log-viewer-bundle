<?php
declare(strict_types=1);

namespace FD\LogViewer\Reader\Stream;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Util\Utils;
use RuntimeException;
use SplFileInfo;

class StreamReaderFactory
{
    public function createForFile(SplFileInfo $file, DirectionEnum $direction, ?int $offset): AbstractStreamReader
    {
        $extension = strtolower(pathinfo($file->getPathname(), PATHINFO_EXTENSION));

        if (Utils::isCompressed($file->getPathname())) {
            if ($direction === DirectionEnum::Desc) {
                throw new RuntimeException('Reading .gz compressed log files in descending order is not supported.');
            }

            return match ($extension) {
                'gz'    => $this->createForGzFile($file, $offset ?? 0),
                default => throw new RuntimeException(sprintf('Unsupported compressed file extension "%s".', $extension)),
            };
        }

        $fileHandle = @fopen($file->getPathname(), 'rb');
        if ($fileHandle === false) {
            throw new RuntimeException(sprintf('Could not open file "%s".', $file->getPathname()));
        }

        if ($direction === DirectionEnum::Desc) {
            return new ReverseStreamReader($fileHandle, $offset);
        }

        return new ForwardStreamReader($fileHandle, $offset ?? 0);
    }

    private function createForGzFile(SplFileInfo $file, int $offset): ForwardStreamReader
    {
        if (extension_loaded('zlib') === false) {
            throw new RuntimeException('The "zlib" PHP extension is required to read .gz compressed log files.');
        }

        $fileHandle = @fopen('compress.zlib://' . $file->getPathname(), 'rb');
        if ($fileHandle === false) {
            throw new RuntimeException(sprintf('Could not open file "%s".', $file->getPathname()));
        }

        return new ForwardStreamReader($fileHandle, $offset);
    }
}
