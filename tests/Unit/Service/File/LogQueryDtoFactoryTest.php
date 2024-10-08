<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File;

use DateTime;
use DateTimeZone;
use Exception;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\File\LogQueryDtoFactory;
use FD\LogViewer\Service\Parser\ExpressionParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(LogQueryDtoFactory::class)]
class LogQueryDtoFactoryTest extends TestCase
{
    private ExpressionParser&MockObject $expressionParser;

    protected function setUp(): void
    {
        parent::setUp();
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
                'sort'      => 'asc',
                'per_page'  => '50',
                'time_zone' => 'America/New_York',
            ]
        );
        $expression = new Expression([]);

        $this->expressionParser->expects(self::once())->method('parse')->with(new StringReader('search'))->willReturn($expression);

        $expected = new LogQueryDto(['file'], new DateTimeZone('America/New_York'), 54321, $expression, DirectionEnum::Asc, 50);
        static::assertEquals($expected, (new LogQueryDtoFactory($this->expressionParser))->create($request));
    }

    /**
     * @throws Exception
     */
    public function testCreateWithDefaults(): void
    {
        $request  = new Request(['file' => 'file']);
        $expected = new LogQueryDto(['file'], (new DateTime())->getTimezone(), null, null, DirectionEnum::Desc, 100);

        $this->expressionParser->expects(self::never())->method('parse');

        static::assertEquals($expected, (new LogQueryDtoFactory($this->expressionParser))->create($request));
    }
}
