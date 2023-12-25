<?php

declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle;

use FD\SymfonyLogViewerBundle\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SymfonyLogViewerBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new Extension();
    }
}
