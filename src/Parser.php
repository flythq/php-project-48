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
        'json' => parseJson($content),
        'yaml', 'yml' => parseYaml($content),
        default => throw new InvalidArgumentException("Unknown parser format '{$extension}'"),
    };
}

/**
 * @throws JsonException
 */
function parseJson(string $json): array
{
    return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
}

function parseYaml(string $yaml): array
{
    return Yaml::parse($yaml);
}
