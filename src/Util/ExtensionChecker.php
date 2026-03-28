<?php
declare(strict_types=1);

namespace FD\LogViewer\Util;

class ExtensionChecker
{
    public function isLoaded(string $extension): bool
    {
        return extension_loaded($extension);
    }
}
