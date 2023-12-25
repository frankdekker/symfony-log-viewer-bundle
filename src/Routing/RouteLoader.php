<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Routing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader extends Loader
{
    private PhpFileLoader $fileLoader;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        /** @var string[]|string $paths */
        $paths            = $kernel->locateResource('@SymfonyLogViewerBundle/Resources/config');
        $this->fileLoader = new PhpFileLoader(new FileLocator($paths));
    }

    public function load(mixed $resource, string $type = null): RouteCollection
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addCollection($this->fileLoader->load('routes.php'));

        return $routeCollection;
    }

    public function supports(mixed $resource, string $type = null): bool
    {
        return $type === 'fd_symfony_log_viewer';
    }
}
