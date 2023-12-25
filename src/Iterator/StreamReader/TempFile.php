<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Iterator\StreamReader;

use RuntimeException;
use SplFileInfo;

class TempFile extends SplFileInfo
{
    /** @var resource|null */
    private $resource;

    public function __construct()
    {
        $resource = tmpfile();
        if ($resource === false) {
            throw new RuntimeException('Unable to create new temp file');
        }
        $this->resource = $resource;
        parent::__construct(stream_get_meta_data($resource)['uri']);

        // a tmpfile is automatically removed when it is closed, the handle is garbage collected or the script ends
        // this temp file remains available till script ends.
        register_shutdown_function(fn() => $this->cleanUp());
    }

    private function cleanUp(): void
    {
        if ($this->resource !== null) {
            @fclose($this->resource);
            $this->resource = null;
        }
    }
}
