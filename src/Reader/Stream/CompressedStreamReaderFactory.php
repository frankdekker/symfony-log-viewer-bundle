<?php
declare(strict_types=1);

namespace FD\LogViewer\Reader\Stream;

use RuntimeException;
use SplFileInfo;

class CompressedStreamReaderFactory
{
    public function createForFile(SplFileInfo $file, ?int $offset): ForwardStreamReader
    {
        return match ($file->getExtension()) {
            'gz'    => $this->createForGzFile($file, $offset ?? 0),
            default => throw new RuntimeException(sprintf('Unsupported compressed file extension "%s".', $file->getExtension())),
        };
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
