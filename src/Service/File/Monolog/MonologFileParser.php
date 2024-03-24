<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Monolog;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogIndexIterator;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\LogFileParserInterface;
use FD\LogViewer\Service\File\LogParser;
use InvalidArgumentException;
use SplFileInfo;

class MonologFileParser implements LogFileParserInterface
{
    public const TYPE_LINE = 'line';
    public const TYPE_JSON = 'json';

    /**
     * @param self::TYPE_* $formatType
     */
    public function __construct(private readonly string $formatType, private readonly LogParser $logParser)
    {
    }

    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogIndexIterator
    {
        return match ($this->formatType) {
            self::TYPE_JSON => $this->logParser->parse(new SplFileInfo($file->path), new MonologJsonParser(), $logQuery),
            self::TYPE_LINE => $this->logParser->parse(
                new SplFileInfo($file->path),
                new MonologLineParser($config->startOfLinePattern, $config->logMessagePattern),
                $logQuery
            ),
            default         => throw new InvalidArgumentException('Invalid format type: ' . $this->formatType),
        };
    }
}
