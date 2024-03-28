<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Apache;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\AbstractLogFileParser;
use SplFileInfo;

class ApacheErrorFileParser extends AbstractLogFileParser
{
    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogRecordCollection
    {
        return $this->logParser->parse(new SplFileInfo($file->path), new ApacheErrorLineParser($config->logMessagePattern), $logQuery);
    }
}
