<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeZone;
use FD\LogViewer\Service\Parser\DateRangeParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;

#[CoversClass(DateRangeParser::class)]
class DateRangeParserTest extends TestCase
{
    private ClockInterface&MockObject $clock;
    private LoggerInterface&MockObject $logger;
    private DateRangeParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clock  = $this->createMock(ClockInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->parser = new DateRangeParser($this->clock);
        $this->parser->setLogger($this->logger);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function testParseDateRangeEmpty(): void
    {
        $this->clock->expects(self::never())->method('now');
        $this->logger->expects(self::never())->method('warning');
        $result = $this->parser->parse('', new DateTimeZone('Europe/Athens'));
        self::assertSame([null, null], $result);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function testParseDateRangeInvalid(): void
    {
        $this->clock->expects(self::never())->method('now');
        $this->logger->expects(self::once())->method('warning');
        $result = $this->parser->parse('foobar', new DateTimeZone('Europe/Athens'));
        self::assertSame([null, null], $result);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function testParseNow(): void
    {
        $date1 = new DateTimeImmutable();
        $date2 = new DateTimeImmutable();

        $this->clock->expects(self::exactly(2))->method('now')->willReturn($date1, $date2);
        [$result1, $result2] = $this->parser->parse('now~now', new DateTimeZone('Europe/Athens'));
        self::assertNotNull($result1);
        self::assertNotNull($result2);
        self::assertSame($date1->getTimestamp(), $result1->getTimestamp());
        self::assertEquals(new DateTimeZone('Europe/Athens'), $result1->getTimezone());
        self::assertSame($date2->getTimestamp(), $result2->getTimestamp());
        self::assertEquals(new DateTimeZone('Europe/Athens'), $result2->getTimezone());
    }

    /**
     * @throws DateMalformedStringException
     */
    #[TestWith(['15s', '-15 seconds'])]
    #[TestWith(['15i', '-15 minutes'])]
    #[TestWith(['15h', '-15 hours'])]
    #[TestWith(['15d', '-15 days'])]
    #[TestWith(['15w', '-15 weeks'])]
    #[TestWith(['15m', '-15 months'])]
    #[TestWith(['15y', '-15 years'])]
    public function testParseRelativeTime(string $interval, string $offset): void
    {
        $date1 = new DateTimeImmutable();
        $date2 = new DateTimeImmutable();

        $this->clock->expects(self::exactly(2))->method('now')->willReturn($date1, $date2);
        [$result1] = $this->parser->parse($interval . '~now', new DateTimeZone('Europe/Athens'));
        self::assertNotNull($result1);
        self::assertSame($date1->setTimezone(new DateTimeZone('Europe/Athens'))->modify($offset)->getTimestamp(), $result1->getTimestamp());
        self::assertEquals(new DateTimeZone('Europe/Athens'), $result1->getTimezone());
    }

    /**
     * @throws DateMalformedStringException
     */
    public function testParseAbsoluteTime(): void
    {
        $date1 = new DateTimeImmutable();
        $date2 = new DateTimeImmutable();

        $this->clock->expects(self::exactly(2))->method('now')->willReturn($date1, $date2);
        [$result1] = $this->parser->parse('2024-12-12 12:34:56~now', new DateTimeZone('Europe/Athens'));
        self::assertNotNull($result1);
        self::assertSame((new DateTimeImmutable('2024-12-12 12:34:56', new DateTimeZone('Europe/Athens')))->getTimestamp(), $result1->getTimestamp());
        self::assertEquals(new DateTimeZone('Europe/Athens'), $result1->getTimezone());
    }
}
