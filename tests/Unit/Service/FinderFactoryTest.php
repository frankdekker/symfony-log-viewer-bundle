<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service;

use FD\LogViewer\Entity\Config\FinderConfig;
use FD\LogViewer\Service\FinderFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(FinderFactory::class)]
class FinderFactoryTest extends TestCase
{
    private FinderFactory $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FinderFactory();
    }

    public function testFindFilesMinimal(): void
    {
        $config   = new FinderConfig(__DIR__, null, false, false);
        $expected = (new Finder())->files()->in([__DIR__])->sortByName();

        static::assertEquals($expected, $this->service->createForConfig($config));
    }

    public function testFindFilesFull(): void
    {
        $config   = new FinderConfig(__DIR__, '*.php', true, true);
        $expected = (new Finder())->ignoreUnreadableDirs()->followLinks()->files()->in([__DIR__])->name(['*.php'])->sortByName();

        static::assertEquals($expected, $this->service->createForConfig($config));
    }
}
