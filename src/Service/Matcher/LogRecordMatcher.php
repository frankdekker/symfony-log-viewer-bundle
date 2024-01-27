<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Index\LogRecord;
use Traversable;

class LogRecordMatcher
{
    /**
     * @param Traversable<int, TermMatcherInterface<TermInterface>> $termMatchers
     */
    public function __construct(private readonly Traversable $termMatchers)
    {
    }

    public function matches(LogRecord $record, Expression $expression): bool
    {
        foreach ($expression->terms as $term) {
            foreach ($this->termMatchers as $termMatcher) {
                if ($termMatcher->supports($term) && $termMatcher->matches($term, $record) === false) {
                    return false;
                }
            }
        }

        return true;
    }
}
