<?php

declare(strict_types=1);

namespace Differ\Utils;

use InvalidArgumentException;

const WORKING_DIRECTORY = __DIR__ . '/../tests/fixtures/';

function getFileContent(string $filePath): string
{
    $realPath = isAbsolutePath($filePath) ? $filePath : getAbsolutePath($filePath);

    if (!file_exists($realPath)) {
        throw new InvalidArgumentException("File not found: {$filePath}");
    }

    $content = file_get_contents($realPath);

    if ($content === false) {
        throw new InvalidArgumentException("Can`t read file: {$filePath}");
    }

    return $content;
}

function getFileExtension(string $filePath): string
{
    return pathinfo($filePath, PATHINFO_EXTENSION);
}

function isAbsolutePath(string $path): bool
{
    if (DIRECTORY_SEPARATOR === '/') {
        return str_starts_with($path, '/');
    }

    return preg_match('/^[a-z]:\\\\/i', $path);
}

function getAbsolutePath(string $path): string
{
    return (string) realpath(WORKING_DIRECTORY . $path);
}
