<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Matcher;

use FD\LogViewer\Entity\Expression\KeyValueTerm;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Index\LogRecord;

/**
 * @implements TermMatcherInterface<KeyValueTerm>
 */
class KeyValueMatcher implements TermMatcherInterface
{
    public function supports(TermInterface $term): bool
    {
        return $term instanceof KeyValueTerm;
    }

    public function matches(TermInterface $term, LogRecord $record): bool
    {
        $data = $term->type === KeyValueTerm::TYPE_CONTEXT ? $record->context : $record->extra;

        return $term->keys === null ? $this->matchValue($term->value, $data) : $this->matchKeysValue($term->keys, $term->value, $data);
    }

    /**
     * @param string|array<int|string, mixed> $data
     */
    private function matchValue(string $term, string|array $data): bool
    {
        if (is_string($data)) {
            return stripos($data, $term) !== false;
        }

        $match = false;
        array_walk_recursive(
            $data,
            function ($value) use (&$match, $term) {
                $match = $match || stripos((string)$value, $term) !== false;
            }
        );

        return $match;
    }

    /**
     * @param string[]                        $keys
     * @param string|array<int|string, mixed> $data
     */
    private function matchKeysValue(array $keys, string $term, string|array $data): bool
    {
        if (is_string($data)) {
            return stripos($data, $term) !== false;
        }

        $value = $data;
        foreach ($keys as $key) {
            if (is_array($value) === false || isset($value[$key]) === false) {
                return false;
            }
            $value = $value[$key];
        }

        return is_scalar($value) && stripos((string)$value, $term) !== false;
    }
}
