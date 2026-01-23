<?php

declare(strict_types=1);

namespace Differ\Differ;

use Exception;

use function Differ\Parser\parse;
use function Differ\Formatters\format;
use function Differ\Utils\getFileContent;
use function Differ\Utils\getFileExtension;
use function Funct\Collection\sortBy;
use function Funct\Collection\union;

const COMPARABLE_TYPES = [
    'added' => 'added',
    'removed' => 'removed',
    'changed' => 'changed',
    'unchanged' => 'unchanged',
    'nested' => 'nested',
];

/**
 * @throws Exception
 */
function genDiff(string $filePath1, string $filePath2, string $format = 'stylish'): string
{
    $data1 = parse(getFileContent($filePath1), getFileExtension($filePath1));
    $data2 = parse(getFileContent($filePath2), getFileExtension($filePath2));

    $diff = buildDiff($data1, $data2);

    return format($diff, $format);
}

function buildDiff(array $data1, array $data2): array
{
    $keys = union(array_keys($data1), array_keys($data2));

    $sortedKeys = sortBy($keys, function ($key) {
        return $key;
    });

    $diff = array_map(function ($key) use ($data1, $data2) {
        $value1 = $data1[$key] ?? null;
        $value2 = $data2[$key] ?? null;

        if (!array_key_exists($key, $data1)) {
            return [
                'key' => $key,
                'type' => COMPARABLE_TYPES['added'],
                'value' => $value2
            ];
        }

        if (!array_key_exists($key, $data2)) {
            return [
                'key' => $key,
                'type' => COMPARABLE_TYPES['removed'],
                'value' => $value1
            ];
        }

        if (
            (is_array($value1) && !array_is_list($value1)) &&
            (is_array($value2) && !array_is_list($value2))
        ) {
            return [
                'key' => $key,
                'type' => COMPARABLE_TYPES['nested'],
                'children' => buildDiff($value1, $value2)
            ];
        }

        if ($value1 !== $value2) {
            return [
                'key' => $key,
                'type' => COMPARABLE_TYPES['changed'],
                'oldValue' => $value1,
                'newValue' => $value2
            ];
        }

        return [
            'key' => $key,
            'type' => COMPARABLE_TYPES['unchanged'],
            'value' => $value1
        ];
    }, $sortedKeys);

    return array_values($diff);
}
