<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\SeverityTerm;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Index\LogRecord;

/**
 * @implements TermMatcherInterface<SeverityTerm>
 */
class SeverityTermMatcher implements TermMatcherInterface
{
    public function supports(TermInterface $term): bool
    {
        return $term instanceof SeverityTerm;
    }

    public function matches(TermInterface $term, LogRecord $record): bool
    {
        foreach ($term->severities as $severity) {
            if (strcasecmp($severity, $record->severity) === 0) {
                return true;
            }
        }

        return false;
    }
}
