<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use Exception;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Entity\Request\SearchQuery;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\ExpressionParser;
use FD\LogViewer\Util\DateUtil;
use Symfony\Component\HttpFoundation\Request;

class LogQueryDtoFactory
{
    public function __construct(private readonly ExpressionParser $expressionParser)
    {
    }

    /**
     * @throws Exception
     */
    public function create(Request $request): LogQueryDto
    {
        $fileIdentifiers = array_filter(explode(',', $request->query->getString('file')), static fn(string $value): bool => trim($value) !== '');
        $offset          = $request->query->get('offset');
        $offset          = $offset === null || $offset === '0' ? null : (int)$offset;
        $query           = trim($request->query->getString('query'));
        $direction       = DirectionEnum::from($request->query->getString('sort', 'desc'));
        $perPage         = $request->query->getInt('per_page', 100);
        $timeZone        = DateUtil::tryParseTimezone($request->query->get('time_zone', ''), date_default_timezone_get());

        // date range
        [$afterDate, $beforeDate] = DateUtil::parseDateRange($request->query->getString('between'), $timeZone);

        // search expression
        $expression = $query === '' ? null : $this->expressionParser->parse(new StringReader($query));

        $searchQuery = null;
        if ($expression !== null || $afterDate !== null || $beforeDate !== null) {
            $searchQuery = new SearchQuery($expression, $afterDate, $beforeDate);
        }

        return new LogQueryDto($fileIdentifiers, $timeZone, $offset, $searchQuery, $direction, $perPage);
    }
}
