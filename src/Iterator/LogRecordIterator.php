<?php
declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\File\LogLineParserInterface;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogRecordIterator implements IteratorAggregate
{
    /**
     * @param Traversable<int, string> $iterator
     */
    public function __construct(
        private readonly Traversable $iterator,
        private readonly LogLineParserInterface $lineParser,
        private readonly string $query
    ) {
    }

    public function getIterator(): Traversable
    {
        foreach ($this->iterator as $message) {
            if ($this->query !== '' && stripos($message, $this->query) === false) {
                continue;
            }

            $lineData = $this->lineParser->parse($message);
            if ($lineData === null) {
                continue;
            }

            yield new LogRecord(
                (int)strtotime($lineData['date']),
                strtolower($lineData['severity']),
                $lineData['channel'],
                $lineData['message'],
                $lineData['context'],
                $lineData['extra']
            );
        }
    }
}
