<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use FD\LogViewer\Entity\Output\DirectionEnum;
use JsonSerializable;

class Paginator implements JsonSerializable
{
    public function __construct(
        public readonly DirectionEnum $direction,
        public readonly bool $first,
        public readonly bool $more,
        public readonly int $offset
    ) {
    }

    /**
     * @return array<string, string|int|bool>
     */
    public function jsonSerialize(): array
    {
        return [
            'direction' => $this->direction->value,
            'first'     => $this->first,
            'more'      => $this->more,
            'offset'    => $this->offset
        ];
    }
}
