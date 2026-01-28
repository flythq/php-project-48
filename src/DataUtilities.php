<?php

declare(strict_types=1);

namespace Differ\DataUtilities;

use InvalidArgumentException;

function getFile(string $filePath): array
{
    $realPath = (string) realpath($filePath);

    if (!file_exists($realPath)) {
        throw new InvalidArgumentException("File not found: {$filePath}");
    }

    $content = file_get_contents($realPath);

    if ($content === false) {
        throw new InvalidArgumentException("Can`t read file: {$filePath}");
    }

    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    return [$content, $extension];
}

function stringify(mixed $value, array $options = []): string
{
    $defaults = [
        'quoteStrings' => false,
        'complexValue' => 'Array',
        'formatArrays' => false,
    ];

    $config = array_merge($defaults, $options);

    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        is_null($value) => 'null',
        is_string($value) => $config['quoteStrings']
            ? sprintf("'%s'", str_replace("'", "\\'", $value))
            : $value,
        is_numeric($value) => (string) $value,
        is_array($value) && !$config['formatArrays'] => $config['complexValue'],
        default => 'unknow type'
    };
}
