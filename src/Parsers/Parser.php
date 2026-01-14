<?php

namespace Differ\Parsers;

use function Differ\Parsers\JsonParser\parse as parseJson;

function parse(string $content, string $format): array
{
    //$format = strtolower($format);

    return match ($format) {
        'json' => parseJson($content),
        default => throw new \Exception("Unknown parser format '{$format}'"),
    };
}
