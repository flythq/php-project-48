<?php

namespace Differ\Parsers;

use InvalidArgumentException;

use function Differ\Parsers\JsonParser\parse as parseJson;
use function Differ\Parsers\YamlParser\parse as parseYaml;

function parse(string $content, string $format): array
{
    return match ($format) {
        'json' => parseJson($content),
        'yaml', 'yml' => parseYaml($content),
        default => throw new InvalidArgumentException("Unknown parser format '{$format}'"),
    };
}
