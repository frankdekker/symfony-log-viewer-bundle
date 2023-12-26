<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\TempFile;
use RuntimeException;
use SplFileInfo;
use ZipArchive;

class ZipArchiveFactory
{
    public function createFromFolder(LogFolder $folder): SplFileInfo
    {
        $archive  = new ZipArchive();
        $tempFile = new TempFile();
        $zipPath  = $tempFile->getPathname();

        if ($archive->open($tempFile->getPathname(), ZipArchive::CREATE) !== true) {
            throw new RuntimeException('Could not open zip file ' . $zipPath . ' for writing');
        }

        foreach ($folder->getFiles() as $file) {
            $archive->addFile($file->path);
        }

        if ($archive->close() === false) {
            throw new RuntimeException('Could not save zip file: ' . $zipPath);
        }

        return $tempFile;
    }
}
