<?php

declare(strict_types=1);

namespace Differ\Parser;

use JsonException;
use InvalidArgumentException;
use Symfony\Component\Yaml\Exception\ParseException;
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
    try {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        throw new JsonException("Invalid JSON: {$e->getMessage()}");
    }

    return $data;
}

function parseYaml(string $yaml): array
{
    try {
        $data = Yaml::parse($yaml);
    } catch (ParseException $e) {
        throw new ParseException("Invalid YAML: {$e->getMessage()}");
    }

    return $data;
}
