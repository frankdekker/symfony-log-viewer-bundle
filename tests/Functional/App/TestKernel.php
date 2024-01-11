<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Functional\App;

use Exception;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);
    }

    /**
     * @return iterable<int|string, BundleInterface>
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new MonologBundle()
        ];
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__, 3) . '/tmp/';
    }

    public function getCacheDir(): string
    {
        return dirname(__DIR__, 3) . '/tmp/';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    /**
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getProjectDir() . "/config/config.yml");
    }
}
