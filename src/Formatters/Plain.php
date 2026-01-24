<?php

declare(strict_types=1);

namespace Differ\Formatters\Plain;

use InvalidArgumentException;

use function Differ\Formatters\stringify;

use const Differ\Differ\VALID_TYPES;

const COMPLEX_VALUE = '[complex value]';

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
            case VALID_TYPES['nested']:
                $lines[] = formatNodes($node['children'], $currentPath);
                break;

            case VALID_TYPES['added']:
                $value = stringify($node['value'], ['quoteStrings' => true, 'complexValue' => COMPLEX_VALUE]);
                $lines[] = "Property '{$currentPath}' was added with value: {$value}";
                break;

            case VALID_TYPES['removed']:
                $lines[] = "Property '{$currentPath}' was removed";
                break;

            case VALID_TYPES['changed']:
                $oldValue = stringify($node['oldValue'], ['quoteStrings' => true, 'complexValue' => COMPLEX_VALUE]);
                $newValue = stringify($node['newValue'], ['quoteStrings' => true, 'complexValue' => COMPLEX_VALUE]);
                $lines[] = "Property '{$currentPath}' was updated. From {$oldValue} to {$newValue}";
                break;

            case VALID_TYPES['unchanged']:
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
