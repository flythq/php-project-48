<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

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
        $currentPath = $path ? "{$path}.{$key}" : $key;

        switch ($type) {
            case COMPARABLE_TYPES['nested']:
                $lines[] = formatNodes($node['children'], $currentPath);
                break;

            case COMPARABLE_TYPES['added']:
                $value = formatValue($node['value']);
                $lines[] = "Property '{$currentPath}' was added with value: {$value}";
                break;

            case COMPARABLE_TYPES['removed']:
                $lines[] = "Property '{$currentPath}' was removed";
                break;

            case COMPARABLE_TYPES['changed']:
                $oldValue = formatValue($node['oldValue']);
                $newValue = formatValue($node['newValue']);
                $lines[] = "Property '{$currentPath}' was updated. From {$oldValue} to {$newValue}";
                break;

            default:
                break;
        }
    }

    return implode("\n", array_filter($lines));
}

function formatValue(mixed $value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_numeric($value)) {
        return (string) $value;
    }

    // Для любых других типов
    return var_export($value, true);
}
