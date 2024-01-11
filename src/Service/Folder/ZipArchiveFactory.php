<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Folder;

use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\TempFile;
use RuntimeException;
use SplFileInfo;
use ZipArchive;

class ZipArchiveFactory
{
    public function createFromFolder(LogFolder $folder): SplFileInfo
    {
        $tmpFile = new TempFile();
        $zipPath = $tmpFile->getPathname();
        $archive = new ZipArchive();

        if ($archive->open($zipPath, ZipArchive::CREATE) !== true) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Could not open zip file ' . $zipPath . ' for writing');
            // @codeCoverageIgnoreEnd
        }

        foreach ($folder->getFiles() as $file) {
            $archive->addFile($file->path);
        }

        if ($archive->close() === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Could not save zip file: ' . $zipPath);
            // @codeCoverageIgnoreEnd
        }

        return $tmpFile;
    }
}
