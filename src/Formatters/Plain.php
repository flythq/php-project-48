<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

use InvalidArgumentException;

use function Differ\Formatters\stringify;

use const Differ\Differ\COMPARABLE_TYPES;

const PLAIN_FORMAT_TEXT = [
    COMPARABLE_TYPES['added'] => 'added',
    COMPARABLE_TYPES['removed'] => 'removed',
    COMPARABLE_TYPES['changed'] => 'updated',
    COMPARABLE_TYPES['nested'] => '[complex value]',
];

function format(array $diff): string
{
    return formatNodes($diff);
}

function formatNodes(array $nodes, string $path = ''): string
{
    $lines = [];

    foreach ($nodes as $node) {
        $key = $node['key'];
        $type = $node['type'];

        if ($path === '') {
            $currentPath = $key;
        } else {
            $currentPath = "{$path}.{$key}";
        }

        switch ($type) {
            case COMPARABLE_TYPES['nested']:
                $lines[] = formatNodes($node['children'], $currentPath);
                break;

            case COMPARABLE_TYPES['added']:
                $value = stringify(
                    $node['value'],
                    ['quoteStrings' => true, 'complexValue' => PLAIN_FORMAT_TEXT[COMPARABLE_TYPES['nested']]]
                );
                $lines[] = "Property '{$currentPath}' was added with value: {$value}";
                break;

            case COMPARABLE_TYPES['removed']:
                $lines[] = "Property '{$currentPath}' was removed";
                break;

            case COMPARABLE_TYPES['changed']:
                $oldValue = stringify(
                    $node['oldValue'],
                    ['quoteStrings' => true, 'complexValue' => PLAIN_FORMAT_TEXT[COMPARABLE_TYPES['nested']]]
                );
                $newValue = stringify(
                    $node['newValue'],
                    ['quoteStrings' => true, 'complexValue' => PLAIN_FORMAT_TEXT[COMPARABLE_TYPES['nested']]]
                );
                $lines[] = "Property '{$currentPath}' was updated. From {$oldValue} to {$newValue}";
                break;

            case COMPARABLE_TYPES['unchanged']:
                $lines[] = '';
                break;

            default:
                throw new InvalidArgumentException("Invalid type state {$type}");
        }
    }

    $filteredLines = array_filter($lines, function ($line) {
        return $line !== '';
    });

    return implode("\n", $filteredLines);
}
