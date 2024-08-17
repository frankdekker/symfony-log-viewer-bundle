<?php

declare(strict_types=1);

namespace FD\LogViewer\Util;

use DateTimeZone;
use Exception;

class DateUtil
{
    /**
     * @throws Exception
     */
    public static function tryParseTimezone(?string $timezone, string $default): DateTimeZone
    {
        if ($timezone === null) {
            return new DateTimeZone($default);
        }

        try {
            return new DateTimeZone($timezone);
        } catch (Exception) {
            return new DateTimeZone($default);
        }
    }
}
