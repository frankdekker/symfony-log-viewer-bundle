<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Request\SearchQuery;
use Traversable;

class LogRecordMatcher
{
    /**
     * @param Traversable<int, TermMatcherInterface<TermInterface>> $termMatchers
     */
    public function __construct(private readonly Traversable $termMatchers)
    {
    }

    public function matches(LogRecord $record, SearchQuery $searchQuery): bool
    {
        if ($searchQuery->afterDate !== null && $record->date < $searchQuery->afterDate->getTimestamp()) {
            return false;
        }

        if ($searchQuery->beforeDate !== null && $record->date > $searchQuery->beforeDate->getTimestamp()) {
            return false;
        }

        foreach ($searchQuery->query->terms ?? [] as $term) {
            foreach ($this->termMatchers as $termMatcher) {
                if ($termMatcher->supports($term) && $termMatcher->matches($term, $record) === false) {
                    return false;
                }
            }
        }

        return true;
    }
}
