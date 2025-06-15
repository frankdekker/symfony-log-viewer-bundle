<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\MessageTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Matcher\MessageTermMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageTermMatcher::class)]
class MessageTermMatcherTest extends TestCase
{
    private MessageTermMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new MessageTermMatcher();
    }

    public function testSupports(): void
    {
        static::assertFalse($this->matcher->supports(new DateBeforeTerm(new DateTimeImmutable())));
        static::assertTrue($this->matcher->supports(new MessageTerm('foobar')));
    }

    public function testMatches(): void
    {
        $record = new LogRecord('id', 'original', 1, 'error', 'channel', 'message', [], []);
        $termFailure = new MessageTerm('foobar');
        $termSuccess = new MessageTerm('mess');

        static::assertFalse($this->matcher->matches($termFailure, $record));
        static::assertTrue($this->matcher->matches($termSuccess, $record));
    }
}
