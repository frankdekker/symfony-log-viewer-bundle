<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service;

use FD\SymfonyLogViewerBundle\Entity\Config\FinderConfig;
use FD\SymfonyLogViewerBundle\Service\FinderService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(FinderService::class)]
class FinderServiceTest extends TestCase
{
    private FinderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FinderService();
    }

    public function testFindFilesMinimal(): void
    {
        $config   = new FinderConfig(__DIR__, null, false, false);
        $expected = (new Finder())->files()->in([__DIR__])->sortByName();

        static::assertEquals($expected, $this->service->findFiles($config));
    }

    public function testFindFilesFull(): void
    {
        $config   = new FinderConfig(__DIR__, '*.php', true, true);
        $expected = (new Finder())->ignoreUnreadableDirs()->followLinks()->files()->in([__DIR__])->name(['*.php'])->sortByName();

        static::assertEquals($expected, $this->service->findFiles($config));
    }
}
