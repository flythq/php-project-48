<?php

namespace Differ\Parsers\YamlParser;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Exception;

function parse(string $yaml): array
{
    try {
        $data = Yaml::parse($yaml);
    } catch (ParseException $e) {
        throw new Exception("Invalid YAML: {$e->getMessage()}");
    }

    return $data;
}
