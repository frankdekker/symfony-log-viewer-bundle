<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\ChannelTerm;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Matcher\ChannelTermMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChannelTermMatcher::class)]
class ChannelTermMatcherTest extends TestCase
{
    private ChannelTermMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new ChannelTermMatcher();
    }

    public function testSupports(): void
    {
        static::assertFalse($this->matcher->supports(new DateBeforeTerm(new DateTimeImmutable())));
        static::assertTrue($this->matcher->supports(new ChannelTerm(['app'])));
    }

    public function testMatches(): void
    {
        $record = new LogRecord('id', 'record', 2000000, 'Warning', 'app', 'message', [], []);

        static::assertTrue($this->matcher->matches(new ChannelTerm(['app']), $record));
        static::assertTrue($this->matcher->matches(new ChannelTerm(['app', 'request']), $record));
        static::assertFalse($this->matcher->matches(new ChannelTerm(['http_client', 'request']), $record));
    }
}
