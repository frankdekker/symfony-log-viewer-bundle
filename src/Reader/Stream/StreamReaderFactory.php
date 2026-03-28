<?php
declare(strict_types=1);

namespace FD\LogViewer\Reader\Stream;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Util\Utils;
use RuntimeException;
use SplFileInfo;

class StreamReaderFactory
{
    public function __construct(private readonly CompressedStreamReaderFactory $compressedFactory)
    {
    }

    public function createForFile(SplFileInfo $file, DirectionEnum $direction, ?int $offset): AbstractStreamReader
    {
        if (Utils::isCompressed($file->getPathname())) {
            if ($direction === DirectionEnum::Desc) {
                throw new RuntimeException('Reading compressed log files in descending order is not supported.');
            }

            return $this->compressedFactory->createForFile($file, $offset);
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
}
