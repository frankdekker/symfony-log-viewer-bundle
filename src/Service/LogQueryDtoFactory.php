<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use Symfony\Component\HttpFoundation\Request;

class LogQueryDtoFactory
{
    public function create(Request $request): LogQueryDto
    {
        $fileIdentifier   = $request->query->get('file', '');
        $offset           = $request->query->get('offset');
        $offset           = $offset === null || $offset === '0' ? null : (int)$offset;
        $query            = $request->query->get('query', '');
        $direction        = DirectionEnum::from($request->query->get('direction', 'desc'));
        $selectedLevels   = array_filter(explode(',', $request->query->get('levels', '')), static fn($level) => $level !== '');
        $selectedChannels = array_filter(explode(',', $request->query->get('channels', '')), static fn($channel) => $channel !== '');
        $perPage          = $request->query->getInt('per_page', 25);

        return new LogQueryDto(
            $fileIdentifier,
            $offset,
            $query,
            $direction,
            $selectedLevels,
            $selectedChannels,
            $perPage
        );
    }
}
