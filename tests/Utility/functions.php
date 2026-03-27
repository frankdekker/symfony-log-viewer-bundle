<?php
declare(strict_types=1);

namespace FD\LogViewer\Reader\Stream;

use FD\LogViewer\Tests\Utility\ExtensionMock;

function extension_loaded(string $extension): bool
{
    if ($extension === 'zlib') {
        return ExtensionMock::$zlibLoaded;
    }

    return \extension_loaded($extension);
}
