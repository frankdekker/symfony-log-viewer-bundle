<?php
declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Request\SearchQuery;
use FD\LogViewer\Service\Matcher\LogRecordMatcher;
use FD\LogViewer\Util\RotatingList;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogRecordFilterIterator implements IteratorAggregate
{
    /** @var RotatingList<LogRecord> */
    private RotatingList $linesBeforeList;
    private int $linesAfter;
    private int $showLinesAfter = 0;

    /**
     * @param iterable<int, LogRecord> $iterator
     */
    public function __construct(private readonly LogRecordMatcher $matcher, private readonly iterable $iterator, private readonly SearchQuery $query)
    {
        $this->linesBeforeList = new RotatingList($query->getLinesBefore());
        $this->linesAfter      = $query->getLinesAfter();
    }

    public function getIterator(): Traversable
    {
        foreach ($this->iterator as $record) {
            if ($this->matcher->matches($record, $this->query)) {
                // if matches, consume everything from the lines before
                foreach ($this->linesBeforeList->getAll() as $contextLine) {
                    $contextLine->setContextLine(true);
                    yield $contextLine;
                }
                $this->linesBeforeList->clear();
                $this->showLinesAfter = $this->linesAfter;
                yield $record;
            } elseif ($this->showLinesAfter > 0) {
                --$this->showLinesAfter;
                $record->setContextLine(true);
                yield $record;
            } else {
                $this->linesBeforeList->add($record);
            }
        }
    }
}
