<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Index\LogRecord;

/**
 * @template T of TermInterface
 */
interface TermMatcherInterface
{
    /**
     * @phpstan-assert-if-true T $term
     */
    public function supports(TermInterface $term): bool;

    /**
     * @phpstan-param T $term
     */
    public function matches(TermInterface $term, LogRecord $record): bool;
}
