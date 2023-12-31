<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Integration;

use PHPUnit\Framework\TestCase;

abstract class AbstractIntegrationTestCase extends TestCase
{
    public function getResourcePath(string $path): string
    {
        return dirname(__DIR__) . '/resources/' . $path;
    }
}
