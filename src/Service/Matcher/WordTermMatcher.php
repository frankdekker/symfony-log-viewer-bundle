<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Entity\Index\LogRecord;

/**
 * @implements TermMatcherInterface<WordTerm>
 */
class WordTermMatcher implements TermMatcherInterface
{
    public function supports(TermInterface $term): bool
    {
        return $term instanceof WordTerm;
    }

    public function matches(TermInterface $term, LogRecord $record): bool
    {
        if ($term->type === WordTerm::TYPE_INCLUDE) {
            if (stripos($record->message, $term->string) === false) {
                return false;
            }
        } elseif ($term->type === WordTerm::TYPE_EXCLUDE) {
            if (stripos($record->message, $term->string) !== false) {
                return false;
            }
        }

        return true;
    }
}
