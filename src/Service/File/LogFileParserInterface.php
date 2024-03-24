<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogIndexIterator;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;

interface LogFileParserInterface
{
    /**
     * @return array<string, string> The key and name of the level
     */
    public function getLevels(): array;

    /**
     * @return array<string, string> They key and name of the channel
     */
    public function getChannels(): array;

    /**
     * Return the LogIndex for the given LogQuery.
     */
    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogIndexIterator;
}
