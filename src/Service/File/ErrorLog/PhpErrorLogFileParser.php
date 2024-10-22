<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\File\ErrorLog;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\AbstractLogFileParser;
use SplFileInfo;

class PhpErrorLogFileParser extends AbstractLogFileParser
{
    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogRecordCollection
    {
        return $this->logParser->parse(new SplFileInfo($file->path), new PhpErrorLogLineParser($config->logMessagePattern), $config, $logQuery);
    }
}
