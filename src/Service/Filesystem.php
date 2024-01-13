<?php
declare(strict_types=1);

namespace FD\LogViewer\Service;

use RuntimeException;
use Traversable;

use function is_array;
use function iterator_to_array;

class Filesystem
{
    /**
     * @param string|iterable<string> $files
     */
    public function remove(string|iterable $files): void
    {
        if ($files instanceof Traversable) {
            $files = iterator_to_array($files, false);
        } elseif (is_array($files) === false) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if (@unlink($file) === false) {
                throw new RuntimeException(sprintf('File `%s` is not deletable (no-write-access).', $file));
            }
        }
    }
}
