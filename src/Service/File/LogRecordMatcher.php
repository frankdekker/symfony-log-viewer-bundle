<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use FD\LogViewer\Entity\Expression\DateAfterTerm;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Entity\Index\LogRecord;

class LogRecordMatcher
{
    public function matches(LogRecord $record, Expression $expression): bool
    {
        foreach ($expression->terms as $term) {
            if ($term instanceof DateBeforeTerm) {
                if ($record->date > $term->date->getTimestamp()) {
                    return false;
                }
                continue;
            }
            if ($term instanceof DateAfterTerm) {
                if ($record->date < $term->date->getTimestamp()) {
                    return false;
                }
                continue;
            }

            if ($term instanceof WordTerm) {
                if ($term->type === WordTerm::TYPE_INCLUDE) {
                    if (stripos($record->message, $term->string) === false) {
                        return false;
                    }
                } elseif ($term->type === WordTerm::TYPE_EXCLUDE) {
                    if (stripos($record->message, $term->string) !== false) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
