<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\DateAfterTerm;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Matcher\DateBeforeTermMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateBeforeTermMatcher::class)]
class DateBeforeTermMatcherTest extends TestCase
{
    private DateBeforeTermMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new DateBeforeTermMatcher();
    }

    public function testSupports(): void
    {
        static::assertTrue($this->matcher->supports(new DateBeforeTerm(new DateTimeImmutable())));
        static::assertFalse($this->matcher->supports(new DateAfterTerm(new DateTimeImmutable())));
    }

    public function testMatches(): void
    {
        $dateBefore = new DateTimeImmutable('@1500000');
        $dateAfter  = new DateTimeImmutable('@2500000');
        $record     = new LogRecord('id', 2000000, 'error', 'channel', 'message', [], []);

        static::assertFalse($this->matcher->matches(new DateBeforeTerm($dateBefore), $record));
        static::assertTrue($this->matcher->matches(new DateBeforeTerm($dateAfter), $record));
    }
}
