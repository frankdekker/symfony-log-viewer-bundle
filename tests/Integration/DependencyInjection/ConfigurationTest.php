<?php
declare(strict_types=1);

namespace DependencyInjection;

use FD\LogViewer\DependencyInjection\Configuration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

#[CoversClass(Configuration::class)]
class ConfigurationTest extends TestCase
{
    private Configuration $configuration;
    private Processor $processor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->processor     = new Processor();
        $this->configuration = new Configuration();
    }

    public function testMinimalConfig(): void
    {
        $configs = [
            'enable_default_monolog' => true,
            'log_files'              => [],
            'hosts'                  => [],
        ];

        $result = $this->processor->processConfiguration($this->configuration, []);

        static::assertSame($configs, $result);
    }

    public function testFullConfig(): void
    {
        $configs = json_decode((string)file_get_contents(__DIR__ . '/data/full-config.json'), true);
        static::assertIsArray($configs);

        $result = $this->processor->processConfiguration($this->configuration, $configs);

        static::assertSame($configs['fd_log_viewer'], $result);
    }
}
