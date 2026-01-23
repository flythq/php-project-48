<?php

declare(strict_types=1);

namespace Differ\Formatters;

use InvalidArgumentException;
use JsonException;

use function Differ\Formatters\Stylish\format as formatStylish;
use function Differ\Formatters\Plain\format as formatPlain;
use function Differ\Formatters\Json\format as formatJson;

const FORMAT_CONFIG = [
    'stylish' => 'stylish',
    'plain' => 'plain',
    'json' => 'json',
];

/**
 * @throws JsonException
 */
function format(array $diff, string $format): string
{
    return match ($format) {
        FORMAT_CONFIG['stylish'] => formatStylish($diff),
        FORMAT_CONFIG['plain'] => formatPlain($diff),
        FORMAT_CONFIG['json'] => formatJson($diff),
        default => throw new InvalidArgumentException("Unknown format: {$format}"),
    };
}
