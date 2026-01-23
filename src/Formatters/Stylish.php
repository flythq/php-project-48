<?php

declare(strict_types=1);

namespace Differ\Formatters\Stylish;

use const Differ\Differ\COMPARABLE_TYPES;

const STYLISH_FORMAT_CONFIG = [
    'indent' => [
        'symbol' => ' ',
        'size' => 4,
        'compareSymbolLength' => 2,
    ],
    'symbols' => [
        COMPARABLE_TYPES['added'] => '+',
        COMPARABLE_TYPES['removed'] => '-',
        COMPARABLE_TYPES['unchanged'] => ' ',
        COMPARABLE_TYPES['nested'] => ' ',
    ],
];

function format(array $diff): string
{
    return formatNodes($diff);
}

function formatNodes(mixed $diffNodes, int $depth = 1): string
{
    if (isSimpleValue($diffNodes)) {
        return stringifyValue($diffNodes, $depth);
    }

    $indent = createIndent($depth);
    $closeBracketIndent = createCloseBracketIndent($depth);

    $lines = array_map(
        fn($node) => formatDiffNode($node, $depth, $indent),
        $diffNodes
    );

    return "{\n" . implode('', $lines) . "{$closeBracketIndent}}";
}

function isSimpleValue(mixed $data): bool
{
    $isNotArray = !is_array($data);
    $isNotDiffStructure = is_array($data) && !isset($data[0], $data[0]['type']);

    return $isNotArray || $isNotDiffStructure;
}

function formatDiffNode(array $node, int $depth, string $indent): string
{
    $key = $node['key'];
    $type = $node['type'];

    return match ($type) {
        COMPARABLE_TYPES['changed'] => formatChangedNode($node, $depth, $indent, $key),
        COMPARABLE_TYPES['nested'] => formatNestedNode($node, $depth, $indent, $key),
        default => formatSimpleDiffNode($node, $depth, $indent, $key, $type)
    };
}

function formatChangedNode(array $node, int $depth, string $indent, string $key): string
{
    $removedLine = formatDiffLine(
        $indent,
        COMPARABLE_TYPES['removed'],
        $key,
        $node['oldValue'],
        $depth
    );

    $addedLine = formatDiffLine(
        $indent,
        COMPARABLE_TYPES['added'],
        $key,
        $node['newValue'],
        $depth
    );

    return $removedLine . $addedLine;
}

function formatNestedNode(array $node, int $depth, string $indent, string $key): string
{
    return formatDiffLine(
        $indent,
        COMPARABLE_TYPES['nested'],
        $key,
        $node['children'],
        $depth
    );
}

function formatSimpleDiffNode(array $node, int $depth, string $indent, string $key, string $type): string
{
    return formatDiffLine($indent, $type, $key, $node['value'], $depth);
}

function formatDiffLine(string $indent, string $type, string $key, mixed $value, int $depth): string
{
    $symbol = getDiffSymbol($type);
    $formattedValue = formatNodes($value, $depth + 1);

    return sprintf("%s%s %s: %s\n", $indent, $symbol, $key, $formattedValue);
}

function stringifyValue(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return convertToString($value);
    }

    $indent = str_repeat(
        STYLISH_FORMAT_CONFIG['indent']['symbol'],
        $depth * STYLISH_FORMAT_CONFIG['indent']['size']
    );

    $closeBracketIndent = str_repeat(
        STYLISH_FORMAT_CONFIG['indent']['symbol'],
        ($depth - 1) * STYLISH_FORMAT_CONFIG['indent']['size']
    );

    $lines = array_map(
        fn($itemKey, $itemValue) => sprintf(
            "%s%s: %s\n",
            $indent,
            $itemKey,
            formatNodes($itemValue, $depth + 1)
        ),
        array_keys($value),
        $value
    );

    return sprintf("{\n%s%s}", implode('', $lines), $closeBracketIndent);
}

function createIndent(int $depth): string
{
    $indentSize = $depth
                  * STYLISH_FORMAT_CONFIG['indent']['size']
                  - STYLISH_FORMAT_CONFIG['indent']['compareSymbolLength'];

    return str_repeat(STYLISH_FORMAT_CONFIG['indent']['symbol'], $indentSize);
}

function createCloseBracketIndent(int $depth): string
{
    $indentSize = $depth * STYLISH_FORMAT_CONFIG['indent']['size'] - STYLISH_FORMAT_CONFIG['indent']['size'];

    return $indentSize > 0
        ? str_repeat(STYLISH_FORMAT_CONFIG['indent']['symbol'], $indentSize)
        : '';
}

function getDiffSymbol(string $type): string
{
    return STYLISH_FORMAT_CONFIG['symbols'][$type] ?? ' ';
}

function convertToString(mixed $value): string
{
    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        is_null($value) => 'null',
        is_string($value) => $value,
        is_numeric($value) => (string) $value,
        default => var_export($value, true)
    };
}
