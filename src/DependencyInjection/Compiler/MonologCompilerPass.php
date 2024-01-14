<?php
declare(strict_types=1);

namespace FD\LogViewer\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @codeCoverageIgnore
 * @internal
 */
final class MonologCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $serviceIds = [];
        foreach ($container->getServiceIds() as $serviceId) {
            if (str_starts_with($serviceId, 'monolog.logger')) {
                $serviceIds[$serviceId] = $serviceId;
            }
        }

        // Add the desired tag to each Monolog logger service
        foreach ($serviceIds as $serviceId) {
            $definition = $container->getDefinition($serviceId);
            if ($definition->isAbstract()) {
                continue;
            }
            $definition->addTag('fd.symfony.log.viewer.logger');
        }
    }
}
