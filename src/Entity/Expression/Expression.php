<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Expression;

class Expression
{
    /**
     * @param TermInterface[] $terms
     */
    public function __construct(public readonly array $terms)
    {
    }

    /**
     * @template T of TermInterface
     * @param class-string<T> $class
     *
     * @return T|null
     */
    public function getTerm(string $class): ?TermInterface
    {
        foreach ($this->terms as $term) {
            if ($term instanceof $class) {
                return $term;
            }
        }

        return null;
    }
}
