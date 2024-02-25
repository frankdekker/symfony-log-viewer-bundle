<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Host;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use InvalidArgumentException;
use Throwable;

class HostServiceBridge
{
    public function __construct(
        private readonly LogFolderOutputProvider $folderOutputProvider,
        private readonly HostProvider $hostProvider,
        private readonly HostInvokeService $hostInvokeService
    ) {
    }

    /**
     * @throws Throwable
     */
    public function getLogFolders(string $host, DirectionEnum $direction): string
    {
        $hostConfig = $this->hostProvider->getHostByKey($host);
        if ($hostConfig === null) {
            throw new InvalidArgumentException('Invalid or unknown host: ' . $host);
        }

        if ($hostConfig->isLocal()) {
            return json_encode($this->folderOutputProvider->provide($direction), JSON_THROW_ON_ERROR);
        }

        return $this->hostInvokeService->request($hostConfig, 'GET', '/folders', ['query' => ['direction' => $direction->value]]);
    }
}
