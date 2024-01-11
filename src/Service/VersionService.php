<?php
declare(strict_types=1);

namespace FD\LogViewer\Service;

use Composer\InstalledVersions;

class VersionService
{
    public function getVersion(): string
    {
        $version = null;
        if (class_exists(InstalledVersions::class)) {
            $version = InstalledVersions::getPrettyVersion('fdekker/symfony-log-viewer-bundle');
        }

        return $version ?? '@dev';
    }
}
