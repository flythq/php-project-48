<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Exception\ParseException;

use function Differ\Differ\genDiff;
use function Differ\Utils\getFileContent;

class DifferTest extends TestCase
{
    private string $diff;

    public function setUp(): void
    {
        $this->diff = getFileContent('diff.txt');
    }

    public function testJsonDiff(): void
    {
        $this->assertEquals($this->diff, genDiff('file1.json', 'file2.json'));

        $this->expectException(\JsonException::class);

        $this->assertEquals($this->diff, genDiff('invalidJson.json', 'invalidJson.json'));
    }

    public function testYamlDiff(): void
    {
        $this->assertEquals($this->diff, genDiff('file1.yaml', 'file2.yaml'));
        $this->assertEquals($this->diff, genDiff('file1.yml', 'file2.yml'));

        $this->expectException(ParseException::class);

        $this->assertEquals($this->diff, genDiff('invalidYaml.yaml', 'invalidYaml.yaml'));
    }
}
