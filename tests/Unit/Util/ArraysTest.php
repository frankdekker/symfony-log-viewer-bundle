<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Util;

use FD\LogViewer\Util\Arrays;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Arrays::class)]
class ArraysTest extends TestCase
{
    public function testMergeSingleDepth(): void
    {
        static::assertSame(['foo' => 'bar'], Arrays::merge([], ['foo' => 'bar']));
        static::assertSame(['foo' => 'bar'], Arrays::merge(['foo' => 'bar'], []));
        static::assertSame(['foo' => 'bar'], Arrays::merge(['foo' => 'bar'], ['foo' => 'baz']));
    }

    public function testMergeMultiDepth(): void
    {
        static::assertSame(
            ['foo' => ['bar' => ['baz' => 'qux']]],
            Arrays::merge(['foo' => []], ['foo' => ['bar' => ['baz' => 'qux']]])
        );
    }

    public function testMergeMultiDepthLists(): void
    {
        static::assertSame(
            ['foo' => ['foo' => 'foo', 'bar' => ['baz', 'qux']]],
            Arrays::merge(['foo' => ['foo' => 'foo']], ['foo' => ['bar' => ['baz', 'qux']]])
        );
    }
}
