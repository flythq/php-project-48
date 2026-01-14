<?php

namespace Differ\Parsers\JsonParser;

function parse(string $json): array
{
    try {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
        throw new \Exception("Invalid JSON: {$e->getMessage()}");
    }

    return $data;
}
