<?php
declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\File\DateTimeParser;
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
        private readonly Traversable            $iterator,
        private readonly DateTimeParser         $dateTimeParser,
        private readonly LogLineParserInterface $lineParser,
    )
    {
    }

    public function getIterator(): Traversable
    {
        foreach ($this->iterator as $message) {
            $identifier = md5($message);
            $lineData = $this->lineParser->parse($message);
            if ($lineData === null) {
                yield new LogRecord($identifier, '', 0, 'error', 'parse', $message, [], []);
                continue;
            }

            $date = $this->dateTimeParser->parse($lineData['date']);
            if ($date === null) {
                yield new LogRecord($identifier, '', 0, 'error', 'bad-date', $message, [], []);
                continue;
            }

            yield new LogRecord(
                md5($identifier),
                $message,
                $date,
                strtolower($lineData['severity']),
                $lineData['channel'],
                $lineData['message'],
                $lineData['context'],
                $lineData['extra']
            );
        }
    }
}
