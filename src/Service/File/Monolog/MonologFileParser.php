<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\File\Monolog;

use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Service\File\LogFileParserInterface;
use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\File\LogParser;
use Monolog\Logger;
use SplFileInfo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MonologFileParser implements LogFileParserInterface
{
    /**
     * @param iterable<int, Logger> $loggerLocator
     */
    public function __construct(
        private readonly iterable $loggerLocator,
        private readonly LogFileService $fileService,
        private readonly LogParser $logParser,
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

    public function getLogIndex(LogQueryDto $logQuery): LogIndex
    {
        $file = $this->fileService->findFileByIdentifier($logQuery->fileIdentifier);
        if ($file === null) {
            throw new NotFoundHttpException(sprintf('Log file with id `%s` not found.', $logQuery->fileIdentifier));
        }

        return $this->logParser->parse(new SplFileInfo($file->path), new MonologLineParser(), $logQuery);
    }
}
