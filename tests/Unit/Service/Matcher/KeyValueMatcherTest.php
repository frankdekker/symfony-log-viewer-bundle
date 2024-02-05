<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\KeyValueTerm;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Matcher\KeyValueMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(KeyValueMatcher::class)]
class KeyValueMatcherTest extends TestCase
{
    private KeyValueMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new KeyValueMatcher();
    }

    public function testSupports(): void
    {
        static::assertFalse($this->matcher->supports(new DateBeforeTerm(new DateTimeImmutable())));
        static::assertTrue($this->matcher->supports(new KeyValueTerm(KeyValueTerm::TYPE_CONTEXT, null, 'value')));
    }

    public function testMatchesAnArrayValue(): void
    {
        $record = new LogRecord(2000000, 'Warning', 'app', 'message', [], ['key' => 'value']);
        $term   = new KeyValueTerm(KeyValueTerm::TYPE_EXTRA, null, 'value');
        static::assertTrue($this->matcher->matches($term, $record));
    }

    public function testMatchesAKeyValue(): void
    {
        $record = new LogRecord(2000000, 'Warning', 'app', 'message', [], ['key' => 'value']);
        $term   = new KeyValueTerm(KeyValueTerm::TYPE_EXTRA, ['key'], 'value');
        static::assertTrue($this->matcher->matches($term, $record));
    }

    public function testMatchesANestedKeyValue(): void
    {
        $record = new LogRecord(2000000, 'Warning', 'app', 'message', [], ['key1' => ['key2' => 'value']]);
        $term   = new KeyValueTerm(KeyValueTerm::TYPE_EXTRA, ['key1', 'key2'], 'value');
        static::assertTrue($this->matcher->matches($term, $record));
    }
}
