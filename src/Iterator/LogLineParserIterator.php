<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Iterator;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Service\File\LogLineParserInterface;
use FD\SymfonyLogViewerBundle\StreamReader\AbstractStreamReader;
use IteratorAggregate;
use Traversable;

/**
 * Iterate over a set of strings. If the string matches the log line either add or append a log line.
 * @implements IteratorAggregate<int, string>
 */
class LogLineParserIterator implements IteratorAggregate
{
    /** @var string[] */
    private array $lines = [];
    private int $position;

    public function __construct(
        private readonly AbstractStreamReader $streamReader,
        private readonly LogLineParserInterface $lineParser,
        private readonly DirectionEnum $direction
    ) {
        $this->position = $this->streamReader->getPosition();
    }

    public function getIterator(): Traversable
    {
        foreach ($this->streamReader as $line) {
            // sniff log line
            $match = $this->lineParser->matches($line);
            if ($match === LogLineParserInterface::MATCH_SKIP) {
                continue;
            }
            if ($match === LogLineParserInterface::MATCH_APPEND) {
                $this->lines[] = $line;
                continue;
            }

            if ($this->direction === DirectionEnum::Asc) {
                // if forward, gather lines till we see the next start of the log line
                if (count($this->lines) === 0) {
                    $this->lines[] = $line;
                    continue;
                }
                $this->position = $this->streamReader->getPosition() - strlen($line);
                yield implode('', $this->lines);
                $this->lines = [$line];
            } else {
                $this->lines[]  = $line;
                $this->position = $this->streamReader->getPosition();
                yield implode('', array_reverse($this->lines));
                $this->lines = [];
            }
        }

        // if lines remaining we should also yield
        if ($this->direction === DirectionEnum::Asc && count($this->lines) > 0) {
            $this->position = $this->streamReader->getPosition();
            yield implode('', $this->lines);
            $this->lines = [];
        }
    }

    public function isEOF(): bool
    {
        return $this->streamReader->isEOF() && count($this->lines) === 0;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
