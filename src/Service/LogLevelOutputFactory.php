<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use Psr\Log\LogLevel;

class LogLevelOutputFactory
{
    // bootstrap 5 text colors
    public const LEVEL_CLASSES = [
        LogLevel::DEBUG     => 'text-info',
        LogLevel::INFO      => 'text-info',
        LogLevel::NOTICE    => 'text-info',
        LogLevel::WARNING   => 'text-warning',
        LogLevel::ERROR     => 'text-danger',
        LogLevel::ALERT     => 'text-danger',
        LogLevel::CRITICAL  => 'text-danger',
        LogLevel::EMERGENCY => 'text-danger',
    ];
}
