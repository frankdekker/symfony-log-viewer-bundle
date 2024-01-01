<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\File;

use InvalidArgumentException;
use Traversable;

class LogFileParserProvider
{
    /**
     * @param Traversable<string, LogFileParserInterface> $logParsers
     */
    public function __construct(private readonly Traversable $logParsers)
    {
    }

    public function get(string $identifier): LogFileParserInterface
    {
        foreach ($this->logParsers as $key => $logParser) {
            if ($key === $identifier) {
                return $logParser;
            }
        }

        throw new InvalidArgumentException(sprintf('Log parser "%s" not found.', $identifier));
    }
}
