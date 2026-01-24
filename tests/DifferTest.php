<?php

declare(strict_types=1);

namespace Differ\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Exception;

use function Differ\Differ\genDiff;
use function Differ\Utils\getFileContent;

class DifferTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('diffProvider')]
    public function testDiff(string $fileType1, string $fileType2, string $format): void
    {
        $actual = genDiff("file1.{$fileType1}", "file2.{$fileType2}", $format);
        $expected = getFileContent("expected/{$format}");
        $this->assertEquals($expected, $actual);
    }

    public static function diffProvider(): array
    {
        return [
            'dataset json/json, -format stylish' => ['json', 'json', 'stylish'],
            'dataset json/yaml, -format stylish' => ['json', 'yaml', 'stylish'],
            'dataset yaml/yml, -format stylish'  => ['yaml', 'yml', 'stylish'],
            'dataset yml/json, -format stylish'  => ['yml', 'json', 'stylish'],

            'dataset json/json, -format plain' => ['json', 'json', 'plain'],
            'dataset json/yaml, -format plain' => ['json', 'yaml', 'plain'],
            'dataset yaml/yml, -format plain'  => ['yaml', 'yml', 'plain'],
            'dataset yml/json, -format plain'  => ['yml', 'json', 'plain'],

            'dataset json/json, -format json' => ['json', 'json', 'json'],
            'dataset json/yaml, -format json' => ['json', 'yaml', 'json'],
            'dataset yaml/yml, -format json'  => ['yaml', 'yml', 'json'],
            'dataset yml/json, -format json'  => ['yml', 'json', 'json'],
        ];
    }

    #[DataProvider('invalidFileProvider')]
    public function testInvalidFilesThrowsException(string $file1, string $file2): void
    {
        $this->expectException(Exception::class);
        genDiff($file1, $file2);
    }

    public static function invalidFileProvider(): array
    {
        return [
            'invalid json file'  => ['invalid.json', 'file2.json'],
            'invalid yaml file' => ['file1.yaml', 'invalid.yaml'],
            'not existing file'   => ['notExist.json', 'file2.yaml'],
        ];
    }
}
