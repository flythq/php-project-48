<?php

declare(strict_types=1);

namespace Differ\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Exception;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return (string) realpath(implode('/', $parts));
    }

    /**
     * @throws Exception
     */
    #[DataProvider('differProvider')]
    public function testDefault(string $firstFilePath, string $secondFilePath): void
    {
        $expected = $this->getFixtureFullPath('stylish.expected');

        $result1 = genDiff(
            $this->getFixtureFullPath($firstFilePath),
            $this->getFixtureFullPath($secondFilePath),
        );
        $this->assertStringEqualsFile($expected, $result1);

        $result2 = genDiff(
            $this->getFixtureFullPath($firstFilePath),
            $this->getFixtureFullPath($firstFilePath),
        );
        $this->assertStringNotEqualsFile($expected, $result2);
    }

    /**
     * @throws Exception
     */
    #[DataProvider('differProvider')]
    public function testStylish(string $firstFilePath, string $secondFilePath): void
    {
        $expected = $this->getFixtureFullPath('stylish.expected');

        $result1 = genDiff(
            $this->getFixtureFullPath($firstFilePath),
            $this->getFixtureFullPath($secondFilePath),
            'stylish'
        );
        $this->assertStringEqualsFile($expected, $result1);

        $result2 = genDiff(
            $this->getFixtureFullPath($firstFilePath),
            $this->getFixtureFullPath($firstFilePath),
            'stylish'
        );
        $this->assertStringNotEqualsFile($expected, $result2);
    }

    /**
     * @throws Exception
     */
    #[DataProvider('differProvider')]
    public function testPlain(string $firstFilePath, string $secondFilePath): void
    {
        $expected = $this->getFixtureFullPath('plain.expected');

        $result1 = genDiff(
            $this->getFixtureFullPath($firstFilePath),
            $this->getFixtureFullPath($secondFilePath),
            'plain'
        );
        $this->assertStringEqualsFile($expected, $result1);

        $result2 = genDiff(
            $this->getFixtureFullPath($firstFilePath),
            $this->getFixtureFullPath($firstFilePath),
            'plain'
        );
        $this->assertStringNotEqualsFile($expected, $result2);
    }

    /**
     * @throws Exception
     */
    #[DataProvider('differProvider')]
    public function testJson(string $firstFilePath, string $secondFilePath): void
    {
        $expected = $this->getFixtureFullPath('json.expected');

        $result1 = genDiff(
            $this->getFixtureFullPath($firstFilePath),
            $this->getFixtureFullPath($secondFilePath),
            'json'
        );
        $this->assertStringEqualsFile($expected, $result1);

        $result2 = genDiff(
            $this->getFixtureFullPath($firstFilePath),
            $this->getFixtureFullPath($firstFilePath),
            'json'
        );
        $this->assertStringNotEqualsFile($expected, $result2);
    }

    public static function differProvider(): array
    {
        return
            [
                'json files' => ['file1.json', 'file2.json'],
                'yaml files' => ['file1.yaml', 'file2.yaml'],
                'yml files' => ['file1.yml', 'file2.yml'],
            ];
    }

    public function testGetFileThrowException(): void
    {
        $this->expectException(Exception::class);
        genDiff('notExist.json', 'file2.json');
    }
}
