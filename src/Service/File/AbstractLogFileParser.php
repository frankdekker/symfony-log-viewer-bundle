<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

abstract class AbstractLogFileParser implements LogFileParserInterface
{
    public function __construct(protected readonly LogParser $logParser)
    {
    }
}
