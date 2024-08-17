<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Utility;

use FD\LogViewer\Entity\Config\FinderConfig;
use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\LogFolderCollection;

trait TestEntityTrait
{
    /**
     * @param array{
     *     logName?: string,
     *     type?: string,
     *     name?: string,
     *     finderConfig?: FinderConfig,
     *     downloadable?: bool,
     *     deletable?: bool,
     *     startOfLinePattern?: string|null,
     *     logMessagePattern?: string|null,
     *     dateFormat?: string|null
     * } $arguments
     */
    public function createLogFileConfig(array $arguments = []): LogFilesConfig
    {
        $arguments += [
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

        $arguments['finderConfig'] ??= new FinderConfig('directory', 'file', null, true, true);

        return new LogFilesConfig(...$arguments);
    }

    /**
     * @param array{
     *     identifier?: string,
     *     path?: string,
     *     relativePath?: string,
     *     earliestTimestamp?: int,
     *     latestTimestamp?: int,
     *     collection?: LogFolderCollection
     * } $arguments
     */
    public function createLogFolder(array $arguments = []): LogFolder
    {
        $arguments += [
            'identifier'        => 'identifier',
            'path'              => 'path',
            'relativePath'      => 'relative-path',
            'earliestTimestamp' => 555555,
            'latestTimestamp'   => 666666,
            'collection'        => null
        ];

        $arguments['collection'] ??= new LogFolderCollection($this->createLogFileConfig());

        return new LogFolder(...$arguments);
    }

    /**
     * @param array{
     *     identifier?: string,
     *     path?: string,
     *     relativePath?: string,
     *     size?: int,
     *     createTimestamp?: int,
     *     updateTimestamp?: int,
     *     folder?: LogFolder|null
     * } $arguments
     */
    public function createLogFile(array $arguments = []): LogFile
    {
        $arguments += [
            'identifier'      => 'identifier',
            'path'            => 'path',
            'relativePath'    => 'relative-path',
            'size'            => 11111,
            'createTimestamp' => 22222,
            'updateTimestamp' => 33333,
            'folder'          => null
        ];

        $arguments['folder'] ??= $this->createLogFolder();

        return new LogFile(...$arguments);
    }
}
