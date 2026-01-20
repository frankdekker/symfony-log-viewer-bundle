<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\Folder;

use FD\LogViewer\Entity\Config\OpenFileConfig;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;

class OpenLogFileDecider
{
    /**
     * @param LogFolder[] $folders
     */
    public function decide(OpenFileConfig $config, array $folders): ?LogFile
    {
        $matches = [];

        // gather all files that match the pattern
        foreach ($folders as $folder) {
            foreach ($folder->getFiles() as $file) {
                if ($config->matches($file->relativePath)) {
                    $matches[] = $file;
                }
            }
        }

        // sort matches by newest or oldest
        usort(
            $matches,
            static fn(LogFile $left, LogFile $right) => $config->order === 'newest'
                ? $right->updateTimestamp <=> $left->updateTimestamp
                : $left->updateTimestamp <=> $right->updateTimestamp
        );

        // take first if any
        return count($matches) === 0 ? null : reset($matches);
    }
}
