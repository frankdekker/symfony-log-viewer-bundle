<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\ChannelTerm;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Index\LogRecord;

/**
 * @implements TermMatcherInterface<ChannelTerm>
 */
class ChannelTermMatcher implements TermMatcherInterface
{
    public function supports(TermInterface $term): bool
    {
        return $term instanceof ChannelTerm;
    }

    public function matches(TermInterface $term, LogRecord $record): bool
    {
        foreach ($term->channels as $channel) {
            if (strcasecmp($channel, $record->channel) === 0) {
                return true;
            }
        }

        return false;
    }
}
