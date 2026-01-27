<?php

declare(strict_types=1);

namespace Differ\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Exception;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $fixturesPath = __DIR__ . '/fixtures/';

    private function getExpectedPath(string $format): string
    {
        return "{$this->fixturesPath}expected/{$format}";
    }

    /**
     * @throws Exception
     */
    public function testDiffWithDefaultFormatOption(): void
    {
        $actual = genDiff(
            "{$this->fixturesPath}file1.json",
            "{$this->fixturesPath}file2.json"
        );

        $expected = $this->getExpectedPath('stylish');

        $this->assertStringEqualsFile($expected, $actual);
    }

    /**
     * @throws Exception
     */
    #[DataProvider('diffProvider')]
    public function testDiffWithFormatOption(string $fileType1, string $fileType2, string $format): void
    {
        $actual = genDiff(
            "{$this->fixturesPath}file1.{$fileType1}",
            "{$this->fixturesPath}file2.{$fileType2}",
            $format
        );
        $expected = $this->getExpectedPath($format);

        $this->assertStringEqualsFile($expected, $actual);
    }

    public static function diffProvider(): array
    {
        return [
            'dataset json/json, -format stylish' => ['json', 'json', 'stylish'],
            'dataset json/yaml, -format stylish' => ['json', 'yml', 'stylish'],

            'dataset yaml/yml, -format plain'  => ['yaml', 'yml', 'plain'],
            'dataset yml/json, -format plain'  => ['yaml', 'json', 'plain'],

            'dataset json/json, -format json' => ['json', 'json', 'json'],
            'dataset yaml/yml, -format json'  => ['yaml', 'yml', 'json'],
        ];
    }

    #[DataProvider('invalidFileProvider')]
    public function testInvalidFilesThrowsException(string $fileType1, string $fileType2): void
    {
        $this->expectException(Exception::class);
        genDiff($fileType1, $fileType2);
    }

    public static function invalidFileProvider(): array
    {
        return [
            'invalid json file'  => ['invalid.json', 'file2.json'],
            'not existing file'   => ['notExist.json', 'file2.yml'],
        ];
    }
}
