<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Index\LogRecord;

class LogLineOutputFactory
{
    /**
     * @param LogRecord[] $lines
     *
     * @return array<string, mixed>
     */
    public function createFromLines(array $lines): array
    {
        $logs = [];

        foreach ($lines as $line) {
            $logs[] = [
                'datetime'    => date('Y-m-d H:i:s', $line->date),
                'level_name'  => ucfirst($line->severity),
                'level_class' => LogLevelOutputFactory::LEVEL_CLASSES[$line->severity] ?? 'text-info',
                'channel'     => $line->channel,
                'text'        => $line->message,
                'context'     => $line->context,
                'extra'       => $line->extra,
            ];
        }

        return $logs;
    }
}
