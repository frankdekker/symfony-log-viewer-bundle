<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use Symfony\Component\Finder\Finder;

class FinderService
{
    public function __construct(private readonly string $logPath)
    {
    }

    public function findFiles(): Finder
    {
        return (new Finder())->ignoreUnreadableDirs()->files()->in($this->logPath)->sortByName();
    }
}
