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

    public function testDefaultConfig(): void
    {
        $configs = self::getJson(__DIR__ . '/data/default-config.json');
        $result  = $this->processor->processConfiguration($this->configuration, []);

        static::assertSame($configs, $result);
    }

    public function testFullConfig(): void
    {
        $configs = self::getJson(__DIR__ . '/data/full-config.json');
        $result  = $this->processor->processConfiguration($this->configuration, $configs);

        static::assertSame($configs['fd_log_viewer'], $result);
    }

    /**
     * @return array<int|string, mixed>
     */
    private static function getJson(string $path): array
    {
        $configs = json_decode((string)file_get_contents($path), true);
        static::assertIsArray($configs);

        return $configs;
    }
}
