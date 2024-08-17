<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use Exception;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\ExpressionParser;
use FD\LogViewer\Service\Parser\InvalidDateTimeException;
use FD\LogViewer\Util\DateUtil;
use Symfony\Component\HttpFoundation\Request;

class LogQueryDtoFactory
{
    public function __construct(private readonly ExpressionParser $expressionParser)
    {
    }

    /**
     * @throws InvalidDateTimeException|Exception
     */
    public function create(Request $request): LogQueryDto
    {
        $fileIdentifiers = array_filter(explode(',', $request->query->get('file', '')));
        $offset          = $request->query->get('offset');
        $offset          = $offset === null || $offset === '0' ? null : (int)$offset;
        $query           = trim($request->query->get('query', ''));
        $direction       = DirectionEnum::from($request->query->get('sort', 'desc'));
        $perPage         = $request->query->getInt('per_page', 100);
        $timeZone        = DateUtil::tryParseTimezone($request->query->getString('timezone'), date_default_timezone_get());

        // search expression
        $expression = $query === '' ? null : $this->expressionParser->parse(new StringReader($query));

        return new LogQueryDto($fileIdentifiers, $timeZone, $offset, $expression, $direction, $perPage);
    }
}
