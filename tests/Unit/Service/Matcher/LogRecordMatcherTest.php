<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use ArrayIterator;
use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Request\SearchQuery;
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

    public function testMatchesEmpty(): void
    {
        $record = new LogRecord('id', 'record', 123, 'error', 'channel', 'message', [], []);

        $this->termMatcher->expects(self::never())->method('supports');

        static::assertTrue($this->matcher->matches($record, new SearchQuery()));
    }

    public function testMatchesAfterDate(): void
    {
        $record      = new LogRecord('id', 'record', 55555, 'error', 'channel', 'message', [], []);
        $searchQuery = new SearchQuery(afterDate: (new DateTimeImmutable())->setTimestamp(55560));

        $this->termMatcher->expects(self::never())->method('supports');

        static::assertFalse($this->matcher->matches($record, $searchQuery));
    }

    public function testMatchesBeforeDate(): void
    {
        $record      = new LogRecord('id', 'record', 55555, 'error', 'channel', 'message', [], []);
        $searchQuery = new SearchQuery(beforeDate: (new DateTimeImmutable())->setTimestamp(55550));

        $this->termMatcher->expects(self::never())->method('supports');

        static::assertFalse($this->matcher->matches($record, $searchQuery));
    }

    public function testMatchesMatch(): void
    {
        $record      = new LogRecord('id', 'record', 123, 'error', 'channel', 'message', [], []);
        $term        = new WordTerm('string', WordTerm::TYPE_INCLUDE);
        $searchQuery = new SearchQuery(new Expression([$term]));

        $this->termMatcher->expects(self::once())->method('supports')->with($term)->willReturn(true);
        $this->termMatcher->expects(self::once())->method('matches')->with($term, $record)->willReturn(true);

        static::assertTrue($this->matcher->matches($record, $searchQuery));
    }

    public function testMatchesNoMatch(): void
    {
        $record      = new LogRecord('id', 'record', 123, 'error', 'channel', 'message', [], []);
        $term        = new WordTerm('string', WordTerm::TYPE_INCLUDE);
        $searchQuery = new SearchQuery(new Expression([$term]));

        $this->termMatcher->expects(self::once())->method('supports')->with($term)->willReturn(true);
        $this->termMatcher->expects(self::once())->method('matches')->with($term, $record)->willReturn(false);

        static::assertFalse($this->matcher->matches($record, $searchQuery));
    }

    public function testMatchesNoSupportedMatchers(): void
    {
        $record      = new LogRecord('id', 'record', 123, 'error', 'channel', 'message', [], []);
        $term        = new WordTerm('string', WordTerm::TYPE_INCLUDE);
        $searchQuery = new SearchQuery(new Expression([$term]));

        $this->termMatcher->expects(self::once())->method('supports')->with($term)->willReturn(false);
        $this->termMatcher->expects(self::never())->method('matches');

        static::assertTrue($this->matcher->matches($record, $searchQuery));
    }
}
