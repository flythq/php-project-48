<?php

declare(strict_types=1);

namespace Differ\Cli;

use function Differ\Differ\genDiff;

const  VERSION = '1.0';
const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]
DOC;

function run(): void
{
    $args = \Docopt::handle(DOC, ['version' => VERSION]);

    try {
        $diff = genDiff($args['<firstFile>'], $args['<secondFile>'], $args['--format']);
        echo $diff . PHP_EOL;
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . PHP_EOL;
        exit(1);
    }
}
