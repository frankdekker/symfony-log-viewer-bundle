<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Matcher;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\KeyValueTerm;
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

    public function testMatches(): void
    {
    }

}
