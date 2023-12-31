<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Integration\Routing;

use Exception;
use FD\SymfonyLogViewerBundle\Routing\RouteLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RouteLoader::class)]
class RouteLoaderTest extends TestCase
{
    private RouteLoader $loader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loader = new RouteLoader();
    }

    /**
     * @throws Exception
     */
    public function testLoad(): void
    {
        $collection = $this->loader->load(null);
        static::assertCount(4, $collection);
    }

    public function testSupports(): void
    {
        static::assertTrue($this->loader->supports(null, 'fd_symfony_log_viewer'));
        static::assertFalse($this->loader->supports(null, ''));
    }
}
