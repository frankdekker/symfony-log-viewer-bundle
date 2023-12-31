<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity;

use RuntimeException;
use SplFileInfo;

use function fclose;
use function register_shutdown_function;
use function stream_get_meta_data;
use function tmpfile;

class TempFile extends SplFileInfo
{
    /** @var resource|null */
    private $resource;

    public function __construct(?callable $registerShutdownFn = null)
    {
        $resource = tmpfile();
        if ($resource === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to create new temp file');
            // @codeCoverageIgnoreEnd
        }
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
