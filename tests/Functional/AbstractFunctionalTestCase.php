<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFunctionalTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient(['environment' => 'test', 'debug' => 'false']);
    }

    /**
     * @template T of object
     * @param class-string<T> $serviceId
     *
     * @return T
     * @throws Exception
     */
    protected static function getService(string $serviceId, ?string $alias = null): object
    {
        /** @var T $service */
        $service = self::getContainer()->get($alias ?? $serviceId);

        return $service;
    }
}
