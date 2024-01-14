<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Output;

enum DirectionEnum: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}
