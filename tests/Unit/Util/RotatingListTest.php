<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Util;

use FD\LogViewer\Util\RotatingList;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(RotatingList::class)]
class RotatingListTest extends TestCase
{
    public function testMutations(): void
    {
        $list = new RotatingList(1);
        $obj1 = new stdClass();
        $obj2 = new stdClass();

        $list->add($obj1);
        static::assertSame([$obj1], $list->getAll());

        $list->add($obj2);
        static::assertSame([$obj2], $list->getAll());

        $list->clear();
        static::assertSame([], $list->getAll());
    }

    public function testListSizeZero(): void
    {
        $list = new RotatingList(0);
        $obj1 = new stdClass();

        $list->add($obj1);
        static::assertSame([], $list->getAll());
    }
}
