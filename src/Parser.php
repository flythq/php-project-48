<?php

declare(strict_types=1);

namespace Differ\Parser;

use InvalidArgumentException;
use JsonException;
use Symfony\Component\Yaml\Yaml;

/**
 * @throws JsonException
 */
function parse(string $content, string $extension): array
{
    return match ($extension) {
        'json' => json_decode($content, true, 512, JSON_THROW_ON_ERROR),
        'yaml', 'yml' => Yaml::parse($content),
        default => throw new InvalidArgumentException("Unknown parser format '{$extension}'"),
    };
}
