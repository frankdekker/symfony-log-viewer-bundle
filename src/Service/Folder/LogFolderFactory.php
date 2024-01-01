<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\Folder;

use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Util\Utils;
use Symfony\Component\Finder\SplFileInfo;

class LogFolderFactory
{
    /**
     * @param iterable<string, SplFileInfo> $files
     */
    public function createFromFiles(LogFilesConfig $config, iterable $files): LogFolderCollection
    {
        $folders = new LogFolderCollection($config);

        foreach ($files as $file) {
            $folder = $folders->getOrAdd($file->getPath(), static fn() => self::createFolder($folders, $file));
            $folder->updateEarliestTimestamp($file->getCTime());
            $folder->updateLatestTimestamp($file->getMTime());
            $folder->addFile(self::createFile($folder, $file));
        }

        return $folders;
    }

    private static function createFolder(LogFolderCollection $collection, SplFileInfo $file): LogFolder
    {
        return new LogFolder(
            Utils::shortMd5($file->getPath()),
            $file->getPath(),
            $file->getRelativePath(),
            $file->getCTime(),
            $file->getMTime(),
            $collection
        );
    }

    private static function createFile(LogFolder $folder, SplFileInfo $file): LogFile
    {
        return new LogFile(
            Utils::shortMd5($file->getPathname()),
            $file->getPathname(),
            $file->getRelativePath(),
            $file->getSize(),
            $file->getCTime(),
            $file->getMTime(),
            $folder
        );
    }
}
