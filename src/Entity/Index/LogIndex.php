<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Index;

class LogIndex
{
    /** @var LogRecord[] */
    private array $lines = [];
    private ?Paginator $paginator = null;

    public function addLine(LogRecord $line): void
    {
        $this->lines[] = $line;
    }

    /**
     * @return LogRecord[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    public function getPaginator(): ?Paginator
    {
        return $this->paginator;
    }

    public function setPaginator(?Paginator $paginator): self
    {
        $this->paginator = $paginator;

        return $this;
    }
}
