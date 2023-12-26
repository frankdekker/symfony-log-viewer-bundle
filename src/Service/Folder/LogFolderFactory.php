<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\Folder;

use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Util\Utils;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LogFolderFactory
{
    public function createFromFiles(Finder $files): LogFolderCollection
    {
        $folders = new LogFolderCollection();

        foreach ($files as $file) {
            $folder = $folders->getOrAdd($file->getPath(), static fn() => self::createFolder($file));
            $folder->updateEarliestTimestamp($file->getCTime());
            $folder->updateLatestTimestamp($file->getMTime());
            $folder->addFile(self::createFile($file));
        }

        return $folders;
    }

    private static function createFolder(SplFileInfo $file): LogFolder
    {
        return new LogFolder(
            Utils::shortMd5($file->getPath()),
            $file->getPath(),
            $file->getRelativePath(),
            $file->getCTime(),
            $file->getMTime(),
        );
    }

    private static function createFile(SplFileInfo $file): LogFile
    {
        return new LogFile(
            Utils::shortMd5($file->getPathname()),
            $file->getPathname(),
            $file->getRelativePath(),
            $file->getSize(),
            $file->getCTime(),
            $file->getMTime(),
        );
    }
}
