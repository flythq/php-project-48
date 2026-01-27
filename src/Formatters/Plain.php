<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

use function Differ\DataUtilities\stringify;

use const Differ\Differ\VALID_TYPES;

const COMPLEX_VALUE = '[complex value]';

function format(array $diff): string
{
    return formatNodes($diff);
}

function formatNodes(array $nodes, string $path = ''): string
{
    $lines = array_map(function ($node) use ($path) {
        $key = $node['key'];
        $type = $node['type'];

        $currentPath = $path === '' ? $key : "{$path}.{$key}";

        if ($type === VALID_TYPES['nested']) {
            return formatNodes($node['children'], $currentPath);
        }

        if ($type === VALID_TYPES['added']) {
            $value = stringify(
                $node['value'],
                [
                    'quoteStrings' => true,
                    'complexValue' => COMPLEX_VALUE
                ]
            );

            return "Property '{$currentPath}' was added with value: {$value}";
        }

        if ($type === VALID_TYPES['removed']) {
            return "Property '{$currentPath}' was removed";
        }

        if ($type === VALID_TYPES['changed']) {
            $oldValue = stringify(
                $node['oldValue'],
                [
                    'quoteStrings' => true,
                    'complexValue' => COMPLEX_VALUE
                ]
            );
            $newValue = stringify(
                $node['newValue'],
                [
                    'quoteStrings' => true,
                    'complexValue' => COMPLEX_VALUE
                ]
            );

            return "Property '{$currentPath}' was updated. From {$oldValue} to {$newValue}";
        }

        return '';
    }, $nodes);

    $filteredLines = array_filter($lines, static fn($line) => $line !== '');

    return implode("\n", $filteredLines);
}
