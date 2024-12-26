<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Index\LogRecord;

/**
 * @deprecated will be removed in v3.0
 * @implements TermMatcherInterface<DateBeforeTerm>
 */
class DateBeforeTermMatcher implements TermMatcherInterface
{
    public function supports(TermInterface $term): bool
    {
        return $term instanceof DateBeforeTerm;
    }

    public function matches(TermInterface $term, LogRecord $record): bool
    {
        return $record->date <= $term->date->getTimestamp();
    }
}
