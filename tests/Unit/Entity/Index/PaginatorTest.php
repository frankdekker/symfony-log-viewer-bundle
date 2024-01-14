<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use FD\LogViewer\Entity\Index\Paginator;
use FD\LogViewer\Entity\Output\DirectionEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Paginator::class)]
class PaginatorTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $paginator = new Paginator(DirectionEnum::Asc, true, true, 123);

        static::assertSame(
            [
                'direction' => 'asc',
                'first'     => true,
                'more'      => true,
                'offset'    => 123,
            ],
            $paginator->jsonSerialize()
        );
    }
}
