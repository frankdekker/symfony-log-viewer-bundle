<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Http;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Index\LogIndexIterator;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\AbstractLogFileParser;
use SplFileInfo;

class HttpAccessFileParser extends AbstractLogFileParser
{
    public function getLogIndex(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogIndexIterator
    {
        return $this->logParser->parse(new SplFileInfo($file->path), new HttpAccessLineParser($config->logMessagePattern), $logQuery);
    }
}
