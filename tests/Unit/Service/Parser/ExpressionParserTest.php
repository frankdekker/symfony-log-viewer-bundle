<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use DateTimeImmutable;
use Exception;
use FD\LogViewer\Entity\Parser\DateAfterTerm;
use FD\LogViewer\Entity\Parser\DateBeforeTerm;
use FD\LogViewer\Entity\Parser\Expression;
use FD\LogViewer\Entity\Parser\WordTerm;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\ExpressionParser;
use FD\LogViewer\Service\Parser\TermParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ExpressionParser::class)]
class ExpressionParserTest extends TestCase
{
    private TermParser&MockObject $termParser;
    private ExpressionParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->termParser = $this->createMock(TermParser::class);
        $this->parser     = new ExpressionParser($this->termParser);
    }

    /**
     * @throws Exception
     */
    public function testParse(): void
    {
        $termA = new DateBeforeTerm(new DateTimeImmutable());
        $termB = new DateAfterTerm(new DateTimeImmutable());
        $termC = new WordTerm('foo', WordTerm::TYPE_INCLUDE);

        $string = $this->createMock(StringReader::class);

        $string->expects(self::exactly(4))->method('eol')->willReturn(false, false, false, true);
        $string->expects(self::exactly(6))->method('skipWhitespace');
        $this->termParser->expects(self::exactly(3))->method('parse')->with($string)->willReturn($termA, $termB, $termC);

        $expected = new Expression([$termA, $termB, $termC]);
        $actual   = $this->parser->parse($string);
        static::assertEquals($expected, $actual);
    }
}
