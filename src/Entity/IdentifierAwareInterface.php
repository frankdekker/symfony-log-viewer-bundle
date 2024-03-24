<?php

declare(strict_types=1);

namespace FD\LogViewer\Entity;

interface IdentifierAwareInterface
{
    /**
     * @return string uniquely identifies this object
     */
    public function getIdentifier(): string;
}
