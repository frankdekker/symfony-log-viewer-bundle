<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Entity\Request\SearchQuery;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\File\LogQueryDtoFactory;
use FD\LogViewer\Service\Parser\DateRangeParser;
use FD\LogViewer\Service\Parser\ExpressionParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(LogQueryDtoFactory::class)]
class LogQueryDtoFactoryTest extends TestCase
{
    private DateRangeParser&MockObject $dateRangeParser;
    private ExpressionParser&MockObject $expressionParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dateRangeParser  = $this->createMock(DateRangeParser::class);
        $this->expressionParser = $this->createMock(ExpressionParser::class);
    }

    /**
     * @throws Exception
     */
    public function testCreate(): void
    {
        $request    = new Request(
            [
                'file'      => 'file',
                'offset'    => '54321',
                'query'     => 'search',
                'between'   => '15i~now',
                'sort'      => 'asc',
                'per_page'  => '50',
                'time_zone' => 'America/New_York',
            ]
        );
        $dateAfter  = new DateTimeImmutable();
        $dateBefore = new DateTimeImmutable();
        $expression = new Expression([]);

        $this->expressionParser->expects(self::once())->method('parse')->with(new StringReader('search'))->willReturn($expression);
        $this->dateRangeParser->expects(self::once())->method('parseDateRange')->with('15i~now')->willReturn([$dateAfter, $dateBefore]);

        $expected = new LogQueryDto(
            ['file'],
            new DateTimeZone('America/New_York'),
            54321,
            new SearchQuery($expression, $dateAfter, $dateBefore),
            DirectionEnum::Asc,
            50
        );
        static::assertEquals($expected, (new LogQueryDtoFactory($this->dateRangeParser, $this->expressionParser))->create($request));
    }

    /**
     * @throws Exception
     */
    public function testCreateWithDefaults(): void
    {
        $request  = new Request(['file' => 'file']);
        $expected = new LogQueryDto(['file'], (new DateTime())->getTimezone(), null, null, DirectionEnum::Desc, 100);

        $this->expressionParser->expects(self::never())->method('parse');
        $this->dateRangeParser->expects(self::once())->method('parseDateRange')->with('')->willReturn([null, null]);

        static::assertEquals($expected, (new LogQueryDtoFactory($this->dateRangeParser, $this->expressionParser))->create($request));
    }
}
