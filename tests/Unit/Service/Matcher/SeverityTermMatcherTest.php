<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\SeverityTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Matcher\SeverityTermMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SeverityTermMatcher::class)]
class SeverityTermMatcherTest extends TestCase
{
    private SeverityTermMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new SeverityTermMatcher();
    }

    public function testSupports(): void
    {
        static::assertFalse($this->matcher->supports(new DateBeforeTerm(new DateTimeImmutable())));
        static::assertTrue($this->matcher->supports(new SeverityTerm(['warning'])));
    }

    public function testMatches(): void
    {
        $record = new LogRecord('id', 'record', 2000000, 'Warning', 'channel', 'message', [], []);

        static::assertTrue($this->matcher->matches(new SeverityTerm(['warning']), $record));
        static::assertTrue($this->matcher->matches(new SeverityTerm(['warning', 'error']), $record));
        static::assertFalse($this->matcher->matches(new SeverityTerm(['info', 'error']), $record));
    }
}
