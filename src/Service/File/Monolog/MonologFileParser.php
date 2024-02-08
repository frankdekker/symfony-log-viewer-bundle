<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Monolog;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\LogFileParserInterface;
use FD\LogViewer\Service\File\LogParser;
use InvalidArgumentException;
use Monolog\Logger;
use SplFileInfo;

class MonologFileParser implements LogFileParserInterface
{
    public const TYPE_LINE = 'line';
    public const TYPE_JSON = 'json';

    /**
     * @param self::TYPE_*          $formatType
     * @param iterable<int, Logger> $loggerLocator
     */
    public function __construct(
        private readonly string $formatType,
        private readonly iterable $loggerLocator,
        private readonly LogParser $logParser
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getLevels(): array
    {
        return [
            'emergency' => 'Emergency',
            'alert'     => 'Alert',
            'critical'  => 'Critical',
            'error'     => 'Error',
            'warning'   => 'Warning',
            'notice'    => 'Notice',
            'info'      => 'Info',
            'debug'     => 'Debug'
        ];
    }

    /**
     * @inheritDoc
     */
    public function getChannels(): array
    {
        $channels = [];
        foreach ($this->loggerLocator as $logger) {
            $channels[$logger->getName()] = $logger->getName();
        }
        ksort($channels);

        return $channels;
    }

    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogIndex
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
