<?php
declare(strict_types=1);

namespace FD\LogViewer\Util;

class Arrays
{
    /**
     * @param array<int|string, mixed> $target
     * @param array<int|string, mixed> $source
     *
     * @return array<int|string, mixed>
     */
    public static function merge(array $target, array $source): array
    {
        foreach ($source as $key => $value) {
            // if target key is not set, assign source value
            if (array_key_exists($key, $target) === false) {
                $target[$key] = $value;
                continue;
            }

            // recursively merge (non-list) arrays
            if (is_array($value)
                && is_array($target[$key])
                && (count($value) === 0 || array_is_list($value) === false)
                && (count($target[$key]) === 0 || array_is_list($target[$key]) === false)) {
                $target[$key] = self::merge($target[$key], $value);
            }
        }

        return $target;
    }
}
