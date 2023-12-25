<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFolderOutput;

class LogFolderOutputProvider
{
    public function __construct(
        private readonly LogFileService $folderService,
        private readonly LogFolderOutputFactory $folderOutputFactory,
        private readonly LogFolderOutputSorter $sorter,
    ) {
    }

    /**
     * @return LogFolderOutput[]
     */
    public function provide(DirectionEnum $direction): array
    {
        // get all file and folders
        $folders = $this->folderService->getFilesAndFolders();
        // create output
        $folderOutputs = $this->folderOutputFactory->createFromFolders($folders);

        // sort based on latest timestamp
        return $this->sorter->sort($folderOutputs, $direction);
    }
}
