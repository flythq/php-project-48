<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

use InvalidArgumentException;

use const Differ\Differ\COMPARABLE_TYPES;

const PLAIN_FORMAT_CONFIG = [
    'text' => [
        COMPARABLE_TYPES['added'] => 'added',
        COMPARABLE_TYPES['removed'] => 'removed',
        COMPARABLE_TYPES['changed'] => 'updated',
        COMPARABLE_TYPES['nested'] => '[complex value]',
    ],
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
                $value = stringify($node['value']);
                $lines[] = "Property '{$currentPath}' was added with value: {$value}";
                break;

            case COMPARABLE_TYPES['removed']:
                $lines[] = "Property '{$currentPath}' was removed";
                break;

            case COMPARABLE_TYPES['changed']:
                $oldValue = stringify($node['oldValue']);
                $newValue = stringify($node['newValue']);
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

function stringify(mixed $value): string
{
    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        is_null($value) => 'null',
        is_string($value) => "'$value'",
        is_numeric($value) => (string) $value,
        is_array($value) => '[complex value]',
        default => var_export($value, true)
    };
}
