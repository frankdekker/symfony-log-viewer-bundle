<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional;

use Exception;
use FD\LogViewer\Util\Utils;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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
        $this->client->catchExceptions(false);
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

    /**
     * @return array<int|string, mixed>
     */
    protected static function responseToArray(Response $response): array
    {
        $json = $response->getContent();
        static::assertIsString($json);

        $data = json_decode($json, true);
        static::assertIsArray($data);

        return $data;
    }

    protected static function getShortMd5(string $relativePath): string
    {
        return Utils::shortMd5((string)realpath(dirname(__DIR__) . '/' . $relativePath));
    }
}
