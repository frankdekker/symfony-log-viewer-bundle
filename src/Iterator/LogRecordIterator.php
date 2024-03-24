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
    ) {
    }

    public function getIterator(): Traversable
    {
        foreach ($this->iterator as $message) {
            $lineData   = $this->lineParser->parse($message);
            if ($lineData === null) {
                yield new LogRecord(md5($message), 0, 'error', 'parse', $message, [], []);
                continue;
            }

            yield new LogRecord(
                md5((string)json_encode($lineData)),
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
