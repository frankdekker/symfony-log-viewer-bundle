<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\DateAfterTerm;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Matcher\DateAfterTermMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateAfterTermMatcher::class)]
class DateAfterTermMatcherTest extends TestCase
{
    private DateAfterTermMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new DateAfterTermMatcher();
    }

    public function testSupports(): void
    {
        static::assertFalse($this->matcher->supports(new DateBeforeTerm(new DateTimeImmutable())));
        static::assertTrue($this->matcher->supports(new DateAfterTerm(new DateTimeImmutable())));
    }

    public function testMatches(): void
    {
        $dateBefore = new DateTimeImmutable('@1500000');
        $dateAfter  = new DateTimeImmutable('@2500000');
        $record     = new LogRecord(2000000, 'error', 'channel', 'message', [], []);

        static::assertTrue($this->matcher->matches(new DateAfterTerm($dateBefore), $record));
        static::assertFalse($this->matcher->matches(new DateAfterTerm($dateAfter), $record));
    }
}
