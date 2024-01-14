<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Monolog;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\LogFileParserInterface;
use FD\LogViewer\Service\File\LogParser;
use Monolog\Logger;
use SplFileInfo;

class MonologFileParser implements LogFileParserInterface
{
    /**
     * @param iterable<int, Logger> $loggerLocator
     */
    public function __construct(private readonly iterable $loggerLocator, private readonly LogParser $logParser)
    {
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
        return $this->logParser->parse(
            new SplFileInfo($file->path),
            new MonologLineParser($config->startOfLinePattern, $config->logMessagePattern),
            $logQuery
        );
    }
}
