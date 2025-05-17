<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Request;

use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Expression\LineAfterTerm;
use FD\LogViewer\Entity\Expression\LineBeforeTerm;
use FD\LogViewer\Entity\Request\SearchQuery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SearchQuery::class)]
class SearchQueryTest extends TestCase
{
    public function testGetLinesBefore(): void
    {
        $query = new SearchQuery();
        static::assertSame(0, $query->getLinesBefore());

        $query = new SearchQuery(query: new Expression([new LineBeforeTerm(5)]));
        static::assertSame(5, $query->getLinesBefore());
    }

    public function testGetLinesAfter(): void
    {
        $query = new SearchQuery();
        static::assertSame(0, $query->getLinesAfter());

        $query = new SearchQuery(query: new Expression([new LineAfterTerm(5)]));
        static::assertSame(5, $query->getLinesAfter());
    }
}
