<?php
declare(strict_types=1);

use FD\LogViewer\FdLogViewerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class TestKernel extends BaseKernel
{
    /**
     * @return iterable<BundleInterface>
     */
    public function registerBundles(): iterable
    {
        return [
            new FdLogViewerBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
    }
}
