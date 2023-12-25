<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Index;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
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
     * @inheritdoc
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
