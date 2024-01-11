<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Folder;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Output\LogFileOutput;
use FD\LogViewer\Entity\Output\LogFolderOutput;

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
            static fn(LogFolderOutput $left, LogFolderOutput $right) => $direction === DirectionEnum::Asc
                ? $left->latestTimestamp <=> $right->latestTimestamp
                : $right->latestTimestamp <=> $left->latestTimestamp
        );

        foreach ($folders as $folder) {
            // sort files
            usort(
                $folder->files,
                static fn(LogFileOutput $left, LogFileOutput $right) => $direction === DirectionEnum::Asc
                    ? $left->latestTimestamp <=> $right->latestTimestamp
                    : $right->latestTimestamp <=> $left->latestTimestamp
            );
        }

        return $folders;
    }
}
