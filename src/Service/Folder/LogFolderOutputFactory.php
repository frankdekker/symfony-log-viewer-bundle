<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Folder;

use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Entity\Output\LogFileOutput;
use FD\LogViewer\Entity\Output\LogFolderOutput;
use FD\LogViewer\Util\Utils;

class LogFolderOutputFactory
{
    /**
     * @return LogFolderOutput[]
     */
    public function createFromFolders(LogFolderCollection $folders): array
    {
        $result = [];
        $config = $folders->config;

        foreach ($folders->toArray() as $folder) {
            $path = $folder->relativePath;

            $result[] = new LogFolderOutput(
                $folder->identifier,
                rtrim($folders->config->name . '/' . $path, '/'),
                $folders->config->downloadable && extension_loaded('zip'),
                $folders->config->deletable,
                $folder->getLatestTimestamp(),
                array_map(fn($file) => $this->createFromFile($file, $config->downloadable, $config->deletable), $folder->getFiles()),
            );
        }

        return $result;
    }

    private function createFromFile(LogFile $file, bool $downloadable, bool $deletable): LogFileOutput
    {
        return new LogFileOutput(
            $file->identifier,
            basename($file->path),
            Utils::bytesForHumans($file->size),
            $file->createTimestamp,
            $file->updateTimestamp,
            $downloadable,
            $deletable
        );
    }
}
