<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;

interface LogFileParserInterface
{
    /**
     * Return the LogRecordCollection for the given LogQuery.
     */
    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogRecordCollection;
}
