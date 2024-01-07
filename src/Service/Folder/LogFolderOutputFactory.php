<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\Folder;

use FD\SymfonyLogViewerBundle\Controller\DownloadFileController;
use FD\SymfonyLogViewerBundle\Controller\DownloadFolderController;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFileOutput;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFolderOutput;
use FD\SymfonyLogViewerBundle\Util\Utils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LogFolderOutputFactory
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * @return LogFolderOutput[]
     */
    public function createFromFolders(LogFolderCollection $folders): array
    {
        $result = [];

        foreach ($folders->toArray() as $folder) {
            $path = $folder->relativePath;

            $result[] = new LogFolderOutput(
                $folder->identifier,
                rtrim($folders->config->name . '/' . $path, '/'),
                $this->urlGenerator->generate(DownloadFolderController::class, ['identifier' => $folder->identifier]),
                $folders->config->downloadable && extension_loaded('zip'),
                $folder->getLatestTimestamp(),
                array_map(fn($file) => $this->createFromFile($file, $folders->config->downloadable), $folder->getFiles()),
            );
        }

        return $result;
    }

    private function createFromFile(LogFile $file, bool $downloadable): LogFileOutput
    {
        return new LogFileOutput(
            $file->identifier,
            basename($file->path),
            Utils::bytesForHumans($file->size),
            $this->urlGenerator->generate(DownloadFileController::class, ['identifier' => $file->identifier]),
            $file->createTimestamp,
            $file->updateTimestamp,
            $downloadable,
        );
    }
}
