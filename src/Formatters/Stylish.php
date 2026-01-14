<?php

namespace Differ\Formatters\Stylish;

function format(array $diff): string
{
    $lines = array_map(function ($node) {
        switch ($node['type']) {
            case 'added':
                $value = convertToString($node['value']);
                return "  + {$node['key']}: {$value}";
            case 'removed':
                $value = convertToString($node['value']);
                return "  - {$node['key']}: {$value}";
            case 'unchanged':
                $value = convertToString($node['value']);
                return "    {$node['key']}: {$value}";
            case 'changed':
                $oldValue = convertToString($node['oldValue']);
                $newValue = convertToString($node['newValue']);
                return "  - {$node['key']}: {$oldValue}" . PHP_EOL . "  + {$node['key']}: {$newValue}";
            default:
                throw new \Exception("Unknown node type: {$node['type']}");
        }
    }, $diff);

    $result = implode(PHP_EOL, $lines);
    return "{\n{$result}\n}";
}

function convertToString(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return (string) $value;
}
