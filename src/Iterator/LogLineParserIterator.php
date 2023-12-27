<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Iterator;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Service\File\LogLineParserInterface;
use IteratorAggregate;
use Traversable;

/**
 * Iterate over a set of strings. If the string matches the log line either add or append a log line.
 * @implements IteratorAggregate<int, string>
 */
class LogLineParserIterator implements IteratorAggregate
{
    /**
     * @param Traversable<int, string> $iterator
     */
    public function __construct(
        private readonly Traversable $iterator,
        private readonly LogLineParserInterface $lineParser,
        private readonly DirectionEnum $direction
    ) {
    }

    public function getIterator(): Traversable
    {
        $lines = [];
        foreach ($this->iterator as $line) {
            // sniff log line
            $match = $this->lineParser->matches($line);
            if ($match === LogLineParserInterface::MATCH_SKIP) {
                continue;
            }
            if ($match === LogLineParserInterface::MATCH_APPEND) {
                $lines[] = $line;
                continue;
            }

            if ($this->direction === DirectionEnum::Asc) {
                // if forward, gather lines till we see the next start of the log line
                if (count($lines) === 0) {
                    $lines[] = $line;
                    continue;
                }
                yield implode('', $lines);
                $lines = [$line];
            } else {
                $lines[] = $line;
                yield implode('', array_reverse($lines));
                $lines = [];
            }
        }

        // if lines remaining we should also yield
        if ($this->direction === DirectionEnum::Asc && count($lines) > 0) {
            yield implode('', $lines);
        }
    }
}
