<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\MessageTerm;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Index\LogRecord;

/**
 * @implements TermMatcherInterface<MessageTerm>
 */
class MessageTermMatcher implements TermMatcherInterface
{
    public function supports(TermInterface $term): bool
    {
        return $term instanceof MessageTerm;
    }

    public function matches(TermInterface $term, LogRecord $record): bool
    {
        return stripos($record->message, $term->searchQuery) !== false;
    }
}
