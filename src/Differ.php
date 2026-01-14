<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Utils\getFileContent;
use function Differ\Utils\getFileExtension;
use function Funct\Collection\sortBy;
use function Funct\Collection\union;
use function Differ\Formatters\Stylish\format as formatStylish;

/**
 * @throws \Exception
 */
function genDiff(string $filePath1, string $filePath2, string $format = 'stylish'): string
{
    $data1 = parse(getFileContent($filePath1), getFileExtension($filePath1));
    $data2 = parse(getFileContent($filePath2), getFileExtension($filePath2));
    //print_r($data1);
    //print_r($data2);

    $diff = buildDiff($data1, $data2);

    return format($diff, $format);
}

function buildDiff(array $data1, array $data2): array
{

    $keys = union(array_keys($data1), array_keys($data2));
    //print_r($keys);
    //$keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    //sort($keys);
    $sortedKeys = sortBy($keys, function ($key) {
        return $key;
    });
    //print_r($sortedKeys);

    $diff = array_map(function ($key) use ($data1, $data2) {
        $value1 = $data1[$key] ?? null;
        $value2 = $data2[$key] ?? null;

        if (!array_key_exists($key, $data1)) {
            return [
                'key' => $key,
                'type' => 'added',
                'value' => $value2
            ];
        }

        if (!array_key_exists($key, $data2)) {
            return [
                'key' => $key,
                'type' => 'removed',
                'value' => $value1
            ];
        }

        /*
        if (is_array($value1) && is_array($value2)) {
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => buildDiff($value1, $value2)
            ];
        }
        */

        if ($value1 !== $value2) {
            return [
                'key' => $key,
                'type' => 'changed',
                'oldValue' => $value1,
                'newValue' => $value2
            ];
        }

        return [
            'key' => $key,
            'type' => 'unchanged',
            'value' => $value1
        ];
    }, $sortedKeys);
    //print_r($diff);
    //print_r(array_values($diff));

    return array_values($diff);
}

function format(array $diff, string $formatName): string
{
    return match ($formatName) {
        'stylish' => formatStylish($diff),
        default => throw new \Exception("Unknown format '{$formatName}'"),
    };
}
