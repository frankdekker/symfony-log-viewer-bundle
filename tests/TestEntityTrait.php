<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests;

use FD\LogViewer\Entity\Config\FinderConfig;
use FD\LogViewer\Entity\Config\LogFilesConfig;
use PHPUnit\Framework\TestCase;

trait TestEntityTrait
{
    private static array $logFilesConfigDefaults = [
        'logName'            => 'logName',
        'type'               => 'monolog',
        'name'               => 'name',
        'finderConfig'       => null,
        'downloadable'       => false,
        'deletable'          => false,
        'startOfLinePattern' => 'patternA',
        'logMessagePattern'  => 'patternB',
        'dateFormat'         => 'Y-m-d'
    ];

    /**
     * @param array{
     *     logName?: string,
     *     type?: string,
     *     name?: string,
     *     finderConfig?: FinderConfig,
     *     downloadable?: bool,
     *     deletable?: bool,
     *     startOfLinePattern?: string,
     *     logMessagePattern?: string,
     *     dateFormat?: string
     * } $arguments
     *
     * @return LogFilesConfig
     */
    public function createLogFileConfig(array $arguments = []): LogFilesConfig
    {
        $arguments += self::$logFilesConfigDefaults;

        if ($arguments['finderConfig'] === null && $this instanceof TestCase) {
            $arguments['finderConfig'] = $this->createMock(FinderConfig::class);
        }

        return new LogFilesConfig(...$arguments);
    }
}
