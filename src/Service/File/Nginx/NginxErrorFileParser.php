<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Nginx;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\AbstractLogFileParser;
use SplFileInfo;

class NginxErrorFileParser extends AbstractLogFileParser
{
    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogIndex
    {
        return $this->logParser->parse(new SplFileInfo($file->path), new NginxErrorLineParser($config->logMessagePattern), $logQuery);
    }
}
