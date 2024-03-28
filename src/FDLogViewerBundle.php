<?php

declare(strict_types=1);

namespace FD\LogViewer;

use FD\LogViewer\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @codeCoverageIgnore
 */
final class FDLogViewerBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new Extension();
    }
}
