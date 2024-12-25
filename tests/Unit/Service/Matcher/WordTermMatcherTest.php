<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use FD\LogViewer\Entity\Expression\SeverityTerm;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Matcher\WordTermMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(WordTermMatcher::class)]
class WordTermMatcherTest extends TestCase
{
    private WordTermMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new WordTermMatcher();
    }

    public function testSupports(): void
    {
        static::assertTrue($this->matcher->supports(new WordTerm('string', WordTerm::TYPE_INCLUDE)));
        static::assertFalse($this->matcher->supports(new SeverityTerm(['error'])));
    }

    public function testMatchesIncludes(): void
    {
        $wordTerm = new WordTerm('string', WordTerm::TYPE_INCLUDE);
        $recordA  = new LogRecord('id', 2000000, 'error', 'channel', 'message', [], []);
        $recordB  = new LogRecord('id', 2000000, 'error', 'channel', 'string string', [], []);

        static::assertFalse($this->matcher->matches($wordTerm, $recordA));
        static::assertTrue($this->matcher->matches($wordTerm, $recordB));
    }

    public function testMatchesExcludes(): void
    {
        $wordTerm = new WordTerm('string', WordTerm::TYPE_EXCLUDE);
        $recordA  = new LogRecord('id', 2000000, 'error', 'channel', 'message', [], []);
        $recordB  = new LogRecord('id', 2000000, 'error', 'channel', 'string string', [], []);

        static::assertTrue($this->matcher->matches($wordTerm, $recordA));
        static::assertFalse($this->matcher->matches($wordTerm, $recordB));
    }
}
