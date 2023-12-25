<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFileOutput;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFolderOutput;

class LogFolderOutputSorter
{
    /**
     * @param LogFolderOutput[] $folders
     *
     * @return LogFolderOutput[]
     */
    public function sort(array $folders, DirectionEnum $direction): array
    {
        // sort folders
        usort(
            $folders,
            static fn(LogFolderOutput $a, LogFolderOutput $b) => $direction === DirectionEnum::Asc
                ? $a->latestTimestamp <=> $b->latestTimestamp
                : $b->latestTimestamp <=> $a->latestTimestamp
        );

        foreach ($folders as $folder) {
            // sort files
            usort(
                $folder->files,
                static fn(LogFileOutput $a, LogFileOutput $b) => $direction === DirectionEnum::Asc
                    ? $a->latestTimestamp <=> $b->latestTimestamp
                    : $b->latestTimestamp <=> $a->latestTimestamp
            );
        }

        return $folders;
    }
}
