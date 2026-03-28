<?php
declare(strict_types=1);

namespace FD\LogViewer\Util;

class Utils
{
    private const GIGABYTE = 1024 * 1024 * 1024;
    private const MEGABYTE = 1024 * 1024;
    private const KILOBYTE = 1024;

    /** @var string[] */
    private const SUPPORTED_COMPRESSED_EXTENSIONS = ['gz'];

    public static function isCompressed(string $path): bool
    {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), self::SUPPORTED_COMPRESSED_EXTENSIONS, true);
    }

    public static function shortMd5(string $content): string
    {
        return substr(md5($content), -8);
    }

    /**
     * Get a human-friendly readable string of the number of bytes provided.
     */
    public static function bytesForHumans(int $bytes): string
    {
        if ($bytes >= self::GIGABYTE) {
            return number_format($bytes / self::GIGABYTE, 2, thousands_separator: '') . ' GB';
        }
        if ($bytes >= self::MEGABYTE) {
            return number_format($bytes / self::MEGABYTE, 2, thousands_separator: '') . ' MB';
        }
        if ($bytes > self::KILOBYTE) {
            // https://en.wiktionary.org/wiki/kB
            return number_format($bytes / self::KILOBYTE, 2, thousands_separator: '') . ' kB';
        }

        return $bytes . ' bytes';
    }
}
