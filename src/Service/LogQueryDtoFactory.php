<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use Symfony\Component\HttpFoundation\Request;

class LogQueryDtoFactory
{
    /**
     * @param array<string, string> $levels
     * @param array<string, string> $channels
     */
    public function create(Request $request, array $levels, array $channels): LogQueryDto
    {
        $fileIdentifier = $request->query->get('file', '');
        $offset         = $request->query->get('offset');
        $offset         = $offset === null || $offset === '0' ? null : (int)$offset;
        $query          = $request->query->get('query', '');
        $direction      = DirectionEnum::from($request->query->get('direction', 'desc'));
        $perPage        = $request->query->getInt('per_page', 25);

        // levels
        $selectedLevels = array_filter(explode(',', $request->query->getString('levels')), static fn($level) => $level !== '');
        if (count($levels) === count($selectedLevels) || count($selectedLevels) === 0) {
            $selectedLevels = null;
        }

        // channels
        $selectedChannels = array_filter(explode(',', $request->query->getString('channels')), static fn($channel) => $channel !== '');
        if (count($channels) === count($selectedChannels) || count($selectedChannels) === 0) {
            $selectedChannels = null;
        }

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
