<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Service\File\LogQueryDtoFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(LogQueryDtoFactory::class)]
class LogQueryDtoFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $request = new Request(
            [
                'file'      => 'file',
                'offset'    => '54321',
                'query'     => 'search',
                'direction' => 'asc',
                'per_page'  => '50',
                'levels'    => 'debug,info',
                'channels'  => 'app,request',
            ]
        );

        $expected = new LogQueryDto('file', 54321, 'search', DirectionEnum::Asc, ['debug', 'info'], ['app', 'request'], 50);
        static::assertEquals($expected, (new LogQueryDtoFactory())->create($request, [], []));
    }

    public function testCreateWithDefaults(): void
    {
        $request  = new Request(['file' => 'file']);
        $expected = new LogQueryDto('file', null, '', DirectionEnum::Desc, null, null, 25);
        static::assertEquals($expected, (new LogQueryDtoFactory())->create($request, ['debug' => 'debug'], ['app' => 'app']));
    }
}
