<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity;

use RuntimeException;
use SplFileInfo;

class TempFile extends SplFileInfo
{
    /** @var resource|null */
    private $resource;

    public function __construct(?callable $registerShutdownFn = null)
    {
        $resource = tmpfile();
        // @codingStandardsIgnoreStart
        if ($resource === false) {
            throw new RuntimeException('Unable to create new temp file');
        }
        // @codingStandardsIgnoreEnd
        $this->resource = $resource;
        parent::__construct(stream_get_meta_data($resource)['uri']);

        // By default, a tmp-file is automatically removed when it is closed, the handle is garbage collected or the script ends.
        // This temp file specifically remains available till script ends.
        ($registerShutdownFn ?? fn($callback) => register_shutdown_function($callback))(fn() => $this->cleanUp());
    }

    private function cleanUp(): void
    {
        if ($this->resource !== null) {
            @fclose($this->resource);
            $this->resource = null;
        }
    }
}
