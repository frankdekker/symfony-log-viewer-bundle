<?php
declare(strict_types=1);

namespace FD\LogViewer\Service;

use Composer\InstalledVersions;
use OutOfBoundsException;

class VersionService
{
    public function getVersion(): string
    {
        $version = null;
        if (class_exists(InstalledVersions::class)) {
            try {
                $version = InstalledVersions::getPrettyVersion('fdekker/log-viewer-bundle');
                // @codeCoverageIgnoreStart
            } catch (OutOfBoundsException) {
                // ignore
                // @codeCoverageIgnoreEnd
            }
        }

        if ($version === null || str_contains($version, 'no-version-set')) {
            // @codeCoverageIgnoreStart
            $version = '@dev';
            // @codeCoverageIgnoreEnd
        }

        return $version;
    }
}
