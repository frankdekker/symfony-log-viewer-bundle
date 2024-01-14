<?php
declare(strict_types=1);

namespace FD\LogViewer\Service;

use FD\LogViewer\Entity\Config\FinderConfig;
use Symfony\Component\Finder\Finder;

class FinderFactory
{
    public function createForConfig(FinderConfig $config): Finder
    {
        $finder = (new Finder());

        if ($config->ignoreUnreadableDirs) {
            $finder->ignoreUnreadableDirs();
        }

        if ($config->followLinks) {
            $finder->followLinks();
        }

        $finder->files()->in(array_map('trim', explode(',', $config->inDirectories)));

        if ($config->fileName !== null) {
            $finder->name(array_map('trim', explode(',', $config->fileName)));
        }

        $finder->sortByName();

        return $finder;
    }
}
