<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;

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
    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogIndex;
}
