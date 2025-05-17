<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Expression;

use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Expression\LineAfterTerm;
use FD\LogViewer\Entity\Expression\LineBeforeTerm;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Expression::class)]
class ExpressionTest extends TestCase
{
    public function testGetTerm(): void
    {
        $term       = new LineBeforeTerm(5);
        $expression = new Expression([$term]);

        static::assertNull($expression->getTerm(LineAfterTerm::class));
        static::assertSame($term, $expression->getTerm(LineBeforeTerm::class));
    }
}
