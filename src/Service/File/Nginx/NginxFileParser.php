<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Nginx;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\LogFileParserInterface;
use FD\LogViewer\Service\File\LogParser;
use InvalidArgumentException;
use SplFileInfo;

class NginxFileParser implements LogFileParserInterface
{
    public function __construct(private readonly string $type, private readonly LogParser $logParser)
    {
    }

    /**
     * @inheritDoc
     */
    public function getLevels(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getChannels(): array
    {
        return [];
    }

    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogIndex
    {
        if ($this->type === 'access') {
            return $this->logParser->parse(new SplFileInfo($file->path), new NginxAccessLineParser($config->logMessagePattern), $logQuery);
        }
        if ($this->type === 'error') {
            return $this->logParser->parse(new SplFileInfo($file->path), new NginxErrorLineParser($config->logMessagePattern), $logQuery);
        }
        throw new InvalidArgumentException('Unknown log type: ' . $this->type);
    }
}
