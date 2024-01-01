<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Config\FinderConfig;
use Symfony\Component\Finder\Finder;

class FinderService
{
    public function findFiles(FinderConfig $config): Finder
    {
        $finder = (new Finder());

        if ($config->ignoreUnreadableDirs) {
            $finder->ignoreUnreadableDirs();
        }

        if ($config->followLinks) {
            $finder->followLinks();
        }

        $finder->files()->in(explode(',', $config->inDirectories));

        if ($config->fileName !== null) {
            $finder->name(explode(',', $config->fileName));
        }

        $finder->sortByName();

        return $finder;
    }
}
