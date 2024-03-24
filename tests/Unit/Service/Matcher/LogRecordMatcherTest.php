<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use ArrayIterator;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Matcher\LogRecordMatcher;
use FD\LogViewer\Service\Matcher\TermMatcherInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Traversable;

#[CoversClass(LogRecordMatcher::class)]
class LogRecordMatcherTest extends TestCase
{
    /** @var TermMatcherInterface<TermInterface>&MockObject */
    private TermMatcherInterface&MockObject $termMatcher;
    private LogRecordMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->termMatcher = $this->createMock(TermMatcherInterface::class);
        /** @var Traversable<int, TermMatcherInterface<TermInterface>> $iterator */
        $iterator      = new ArrayIterator([$this->termMatcher]);
        $this->matcher = new LogRecordMatcher($iterator);
    }

    public function testMatchesMatch(): void
    {
        $record     = new LogRecord('id', 123, 'error', 'channel', 'message', [], []);
        $term       = new WordTerm('string', WordTerm::TYPE_INCLUDE);
        $expression = new Expression([$term]);

        $this->termMatcher->expects(self::once())->method('supports')->with($term)->willReturn(true);
        $this->termMatcher->expects(self::once())->method('matches')->with($term, $record)->willReturn(true);

        static::assertTrue($this->matcher->matches($record, $expression));
    }

    public function testMatchesNoMatch(): void
    {
        $record     = new LogRecord('id', 123, 'error', 'channel', 'message', [], []);
        $term       = new WordTerm('string', WordTerm::TYPE_INCLUDE);
        $expression = new Expression([$term]);

        $this->termMatcher->expects(self::once())->method('supports')->with($term)->willReturn(true);
        $this->termMatcher->expects(self::once())->method('matches')->with($term, $record)->willReturn(false);

        static::assertFalse($this->matcher->matches($record, $expression));
    }

    public function testMatchesNoSupportedMatchers(): void
    {
        $record     = new LogRecord('id', 123, 'error', 'channel', 'message', [], []);
        $term       = new WordTerm('string', WordTerm::TYPE_INCLUDE);
        $expression = new Expression([$term]);

        $this->termMatcher->expects(self::once())->method('supports')->with($term)->willReturn(false);
        $this->termMatcher->expects(self::never())->method('matches');

        static::assertTrue($this->matcher->matches($record, $expression));
    }
}
