<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity;

use RuntimeException;
use SplFileInfo;

use function sys_get_temp_dir;
use function tempnam;
use function unlink;

class TempFile extends SplFileInfo
{
    public function __construct()
    {
        $path = tempnam(sys_get_temp_dir(), 'log_viewer_zip_archive');
        if ($path === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to create new temp file');
            // @codeCoverageIgnoreEnd
        }
        parent::__construct($path);
    }

    public function __destruct()
    {
        if ($this->isFile()) {
            @unlink($this->getPathname());
        }
    }
}
